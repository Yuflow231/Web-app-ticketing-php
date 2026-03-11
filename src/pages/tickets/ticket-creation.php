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
    $debugHandler->addForwardedParams();

    // Load projects for dropdown based on role
    $projects = [];
    try {
        $db = DBHandler::getInstance();
        $projects = $db->getProjectsForUser($user['id'], $user['role']);
    } catch (Exception $e) {
        $debugHandler->addInfoRight("DB Error", $e->getMessage());
    }

    // Handle form submission
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $name        = trim($_POST['ticket-title']       ?? '');
        $projectId   = (int) ($_POST['ticket-project']   ?? 0);
        $priority    = $_POST['ticket-priority']         ?? 'Medium';
        $description = trim($_POST['ticket-description'] ?? '');
        $estimated   = !empty($_POST['estimated-time']) ? (float) $_POST['estimated-time'] : 0;

        try {
            $ticketId = $db->createTicket($name, $projectId, $priority, $description, $estimated);
            // Auto-assign creator as 'Ticket Creator'
            $db->assignTicketWorker($ticketId, $user['id'], 'Ticket Creator');
            header("Location: ../tickets/ticket-details.php?id={$ticketId}&toast=ticket_created" . $debugHandler->getDebugAppend());
            exit;
        } catch (Exception $e) {
            $fwd = $debugHandler->getDebugForwardParams(['DB Error' => $e->getMessage()]);
            header("Location: ./ticket-creation.php?toast=db_error" . $debugHandler->getDebugAppend() . $fwd);
            exit;
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ticket creation - Ticketing App</title>
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
            <h1>Ticket creation</h1>
        </header>

        <form id="ticket-form" class="form-box" method="POST" enctype="multipart/form-data">
            <div class="form-2elements">
                <div class="form-item-stacked">
                    <label for="ticket-title">Ticket's object *</label>
                    <input type="text" id="ticket-title" name="ticket-title" placeholder="Ticket's object">
                </div>
                <div class="form-item-stacked">
                    <label for="ticket-project">Associated project *</label>
                    <select id="ticket-project" name="ticket-project">
                        <option value="" disabled selected hidden>Select a project</option>
                        <?php foreach ($projects as $project): ?>
                            <option value="<?= $project['id'] ?>"><?= $project['name'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="form-2elements">
                <!--
                <div class="form-item-stacked">
                    <label for="due-date">Due to *</label>
                    <input type="date" id="due-date" name="due-date">
                </div>
                -->
                <div class="form-item-stacked">
                    <label for="ticket-priority">Priority *</label>
                    <select id="ticket-priority" name="ticket-priority">
                        <option value="" disabled selected hidden>Select a priority</option>
                        <option value="Low">Low</option>
                        <option value="Medium">Medium</option>
                        <option value="High">High</option>
                    </select>
                </div>
            </div>

            <div class="form-item-stacked">
                <label for="ticket-description">Description</label>
                <textarea id="ticket-description" name="ticket-description" rows="8" placeholder="Ticket's description"></textarea>
            </div>

            <div class="form-item-stacked">
                <label for="drop-file">Attached files</label>
                <div id="drop-zone">
                    <p>Drag and drop files here or click to select files</p>
                    <input type="file" id="drop-file" name="drop-file[]" multiple style="display: none;">
                </div>
                <ul id="file-list"></ul>
            </div>

            <div class="centered" style="display: flex; gap: var(--spacing-md); justify-content: center;">
                <button onclick="location.href = 'tickets.php<?=  $debugHandler->getDebugParam() ?>'" type="button" class="btn btn--outline">Cancel</button>
                <button id="actions" class="btn" type="submit">Create ticket</button>
            </div>
        </form>
    </main>
</body>
<script type="module">
    // Set as module to allow imports
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

    // Get references to form's field
    let formTitle = document.getElementById("ticket-title");
    let formProject = document.getElementById("ticket-project");

    // let formDate = document.getElementById("due-date");
    let formPriority = document.getElementById("ticket-priority");

    // prevent multiple request when the infos are correct
    let canPress = true;

    // Get reference to form button
    let formButton = document.getElementById("actions");

    // Add click event on the previously got button
    formButton.addEventListener("click", (e) => {
        e.preventDefault();
        if (canPress){
            verifyForm();
        }
    });

    function verifyForm(){
        let formValidation = true;
        // Clear previous highlights
        FormVerifier.resetFormState([formTitle, formProject, formPriority]);

        // check each field with its corresponding checks
        formValidation &= FormVerifier.checkField(formTitle, formTitle, [FormVerifier.verifyEmptyness("Please enter a ticket title")]);
        formValidation &= FormVerifier.checkField(formProject, formProject, [FormVerifier.verifyEmptyness("Please chose the associated project")]);
        // formValidation &= FormVerifier.checkField(formDate, formDate, [FormVerifier.verifyEmptyness("Please select a due date"), FormVerifier.verifyDate("Due date cannot be in the past")]);
        formValidation &= FormVerifier.checkField(formPriority, formPriority, [FormVerifier.verifyEmptyness("Please chose the priority level")]);

        // if everything checks out
        if(formValidation){
            canPress = false;

            DragDrop.syncFilesToInput();
            FormVerifier.validateForm("Creating ticket ...");
            // force the submission of the form, without the preventDefault (keep the form validation logic)
            setTimeout(() => {
                document.getElementById("ticket-form").submit();
            }, 1500);
        }
    }
</script>
</html>
