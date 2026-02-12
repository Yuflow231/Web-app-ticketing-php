<?php
    require_once("../../assets/php/debug-handler.php");
    // Initialize debug handler
    $debugHandler = DebugHandler::getInstance();

    $debugHandler->addPostParams();
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
    <script type="module" src="../../assets/js/drag-n-drop.js"></script>
</head>
<body>
    <!-- Navigation Bar -->
    <?php require_once("../../assets/php/side-nav-component.php"); ?>

    <!-- Main Content -->
    <main class="main-content">
        <header class="page-header">
            <h1>Project creation</h1>
        </header>

        <form id="project-form" class="form-box" method="POST">
            <div class="form-2elements">
                <div class="form-item-stacked">
                    <label for="project-name">Project's name *</label>
                    <input type="text" id="project-name" name="project-name" placeholder="Project's name" required>
                </div>
                <div class="form-item-stacked">
                    <label for="client-name">Client *</label>
                    <input type="text" id="client-name" name="client-name" placeholder="Client name" required>
                </div>
            </div>

            <div class="form-2elements">
                <div class="form-item-stacked">
                    <label for="start-date">Starting date</label>
                    <input type="date" id="start-date" name="start-date">
                </div>
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
                    <input type="file" id="drop-file" name="drop-file" style="display: none;">
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
    // Set as module to allow imports
    import * as FormVerifier from "../../assets/js/form-verifs.js";

    // Get references to form's field
    let formTitle = document.getElementById("project-name");
    let formClient = document.getElementById("client-name");

    let formStart = document.getElementById("start-date");
    let formEnd = document.getElementById("end-date");

    let formStatus = document.getElementById("project-status");

    let formFiles = document.getElementById("file-list");
    let formDrop = document.getElementById("drop-zone");

    // prevent multiple request when the infos are correct
    let canPress = true;

    // Get reference to form button
    let fromButton = document.getElementById("actions");

    // Add click event on the previously got button
    fromButton.addEventListener("click", (e) => {
        e.preventDefault();
        if (canPress){
            verifyForm();
        }
        //console.log(formFiles.childElementCount);
    });

    function verifyForm(){
        let formValidation = true;
        // Clear previous highlights
        FormVerifier.resetFormState([formTitle, formClient, formStart, formEnd, formStatus, formDrop]);

        // check each field with its corresponding checks
        formValidation &= FormVerifier.checkField(formTitle, formTitle, [FormVerifier.verifyEmptyness("Please enter a project's name")]);
        formValidation &= FormVerifier.checkField(formClient, formClient, [FormVerifier.verifyEmptyness("Please enter the client name")]);

        formValidation &= FormVerifier.checkField(formStart, formStart, [FormVerifier.verifyEmptyness("Please select a starting date"), FormVerifier.verifyDate("Starting date cannot be in the past")]);
        formValidation &= FormVerifier.checkField(formEnd, formEnd, [FormVerifier.verifyEmptyness("Please select a ending date"), FormVerifier.verifyDate("Ending date cannot be in the past")]);
        formValidation &= FormVerifier.checkField([formStart,formEnd], formEnd, [FormVerifier.verifyDateDiff("The ending date must be after the starting date")]);


        formValidation &= FormVerifier.checkField(formStatus, formStatus, [FormVerifier.verifyEmptyness("Please select the project's status")]);
        formValidation &= FormVerifier.checkField(formFiles, formDrop, [FormVerifier.verifyFile("Please send the project contract")]);

        // if everything checks out
        if(formValidation){
            canPress = false;

            if(<?= json_encode($debugHandler->isEnabled()) ?>){ // need json_encode due to php's false being an empty character
                FormVerifier.validateForm("Creating project ...");
                // force the submission of the form, without the preventDefault (keep the form validation logic)
                setTimeout(() => {
                    document.getElementById("project-form").submit();
                }, 1500);
            }
            else{
                FormVerifier.validateForm("Creating project ...", "/src/pages/projects/projects.php<?=  $debugHandler->getDebugParam() ?>");
            }
        }
    }
</script>
</html>