<?php
session_start();
require_once("../../assets/php/debug-handler.php");
require_once("../../assets/php/db-handler.php");

// Guard — kick back to login if not authenticated
if (!isset($_SESSION['user'])) {
    header("Location: ../../../index.php?toast=not_logged_in");
    exit;
}

$user = $_SESSION['user'];
$debugHandler = DebugHandler::getInstance();
$debugHandler->addPostParams();
$debugHandler->addFileParams();
$debugHandler->addForwardedParams(); // picks up DB errors forwarded from redirects


// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name        = trim($_POST['project-name']        ?? '');
    $ownerId     = (int) ($_POST['owner-id']          ?? $user['id']);
    $status      = $_POST['project-status']           ?? '';
    $description = trim($_POST['project-description'] ?? '');
    $endDate     = $_POST['end-date']                 ?? null;
    $estimated   = 0;

    try {
        $db        = DBHandler::getInstance();
        $projectId = $db->createProject($name, $ownerId, $description, $estimated, $status, $endDate);
        header("Location: ./project-details.php?id={$projectId}&toast=project_created" . $debugHandler->getDebugAppend());
        exit;
    } catch (Exception $e) {
        $fwd = $debugHandler->getDebugForwardParams(['DB Error' => $e->getMessage()]);
        header("Location: ./project-creation.php?toast=db_error" . $debugHandler->getDebugAppend() . $fwd);
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Project creation - Ticketing App</title>
    <link rel="stylesheet" href="../../assets/css/main.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/js/all.min.js" defer></script>
    <script type="module" src="../../assets/js/side-bar.js"></script>
</head>
<body>
<!-- Navigation Bar -->
<?php require_once("../../assets/php/side-nav-component.php"); ?>

<!-- Main Content -->
<main class="main-content">
    <header class="page-header">
        <h1>Project creation</h1>
    </header>

    <form id="project-form" class="form-box" method="POST" enctype="multipart/form-data">
        <div class="form-2elements">
            <div class="form-item-stacked">
                <label for="project-name">Project's name *</label>
                <input type="text" id="project-name" name="project-name" placeholder="Project's name" required>
            </div>
            <div class="form-item-stacked">
                <label>Owner *</label>
                <!-- Guest: locked to self -->
                <input type="hidden" name="owner-id" value="<?= $user['id'] ?>">
                <div class="user-profile-inline" style="padding: var(--spacing-sm); border: 1px solid #ddd; border-radius: var(--radius-md); opacity: 0.8;">
                    <img src="../../../src/assets/images/<?= $user["profile_pic"] ?>" class="profile-pic-mini" alt="profile-pic">
                    <span style="margin-left: var(--spacing-sm)"><?= $user['first_name'] . ' ' . $user['last_name'] ?></span>
                </div>
            </div>
        </div>

        <div class="form-2elements">
            <!--
            <div class="form-item-stacked">
                <label for="start-date">Starting date</label>
                <input type="date" id="start-date" name="start-date">
            </div> -->
            <div class="form-item-stacked">
                <label for="end-date">Estimated ending date</label>
                <input type="date" id="end-date" name="end-date">
            </div>
        </div>

        <div class="form-item-stacked">
            <label for="project-status">Status</label>
            <select id="project-status" name="project-status">
                <option value="" selected hidden disabled>Select a status</option>
                <option value="New">New</option>
                <option value="In Progress">In Progress</option>
                <option value="On Hold">On Hold</option>
            </select>
        </div>

        <div class="form-item-stacked">
            <label for="project-description">Description</label>
            <textarea id="project-description" name="project-description" rows="6" placeholder="Project's description"></textarea>
        </div>

        <div class="form-item-stacked">
            <label for="drop-file">Attached files</label>
            <div id="drop-zone">
                <p>Drag and drop files here or click to select files</p>
                <input type="file" id="drop-file" name="drop-file[]" style="display: none;">
            </div>
            <ul id="file-list"></ul>
        </div>

        <div class="centered" style="display: flex; gap: var(--spacing-md); justify-content: center;">
            <button onclick="location.href = './projects.php<?=  $debugHandler->getDebugParam() ?>'" type="button" class="btn btn--outline">Cancel</button>
            <button id="actions" class="btn" type="submit">Create project</button>
        </div>
    </form>
</main>
</body>
<script type="module">
    import * as FormVerifier from "../../assets/js/form-verifs.js";
    import * as DragDrop from "../../assets/js/drag-n-drop.js";
    import Toast from "../../assets/js/toast.js";

    // Toast from redirect
    const toastMessages = {
        db_error: { text: "A database error occurred.", type: "error" },
    };
    const params = new URLSearchParams(window.location.search);
    const toastKey = params.get('toast');
    if (toastKey && toastMessages[toastKey]) {
        Toast(toastMessages[toastKey].text, toastMessages[toastKey].type);
        const cleanUrl = new URL(window.location.href);
        cleanUrl.searchParams.delete('toast');
        window.history.replaceState({}, '', cleanUrl);
    }

    // Form fields
    let formTitle  = document.getElementById("project-name");
    // let formStart  = document.getElementById("start-date");
    let formEnd    = document.getElementById("end-date");
    let formStatus = document.getElementById("project-status");
    let formFiles  = document.getElementById("file-list");
    let formDrop   = document.getElementById("drop-zone");

    let canPress = true;
    let formButton = document.getElementById("actions");

    formButton.addEventListener("click", (e) => {
        e.preventDefault();
        if (canPress) verifyForm();
    });

    function verifyForm() {
        let formValidation = true;
        FormVerifier.resetFormState([formTitle, formEnd, formStatus, formDrop]);

        formValidation &= FormVerifier.checkField(formTitle, formTitle, [FormVerifier.verifyEmptyness("Please enter a project's name")]);
        // formValidation &= FormVerifier.checkField(formStart, formStart, [FormVerifier.verifyEmptyness("Please select a starting date"), FormVerifier.verifyDate("Starting date cannot be in the past")]);
        formValidation &= FormVerifier.checkField(formEnd, formEnd, [FormVerifier.verifyEmptyness("Please select an ending date"), FormVerifier.verifyDate("Ending date cannot be in the past")]);
        // formValidation &= FormVerifier.checkField([formStart, formEnd], formEnd, [FormVerifier.verifyDateDiff("The ending date must be after the starting date")]);
        formValidation &= FormVerifier.checkField(formStatus, formStatus, [FormVerifier.verifyEmptyness("Please select the project's status")]);
        formValidation &= FormVerifier.checkField(formFiles, formDrop, [FormVerifier.verifyFile("Please send the project contract")]);

        if (formValidation) {
            canPress = false;
            DragDrop.syncFilesToInput();
            FormVerifier.validateForm("Creating project ...");
            setTimeout(() => {
                document.getElementById("project-form").submit();
            }, 1500);
        }
    }
</script>
</html>