<?php
    require_once("../assets/php/debug-handler.php");
    // Initialize debug handler
    $debugHandler = DebugHandler::getInstance();

    $debugHandler->addInfoLeft('Test', 'passward');

    $debugHandler->addPostParams();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account creation - Ticketing App</title>
    <link rel="stylesheet" href="../../src/assets/css/main.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/js/all.min.js" defer></script>
</head>
<body>
    <div id="login-box">
        <form id="creation-form" class="form-content card-wo-hover" method="POST">
            <h2 style="text-align: center">Account creation</h2>

            <div class="form-item-stacked">
                <label for="form-first">First Name</label>
                <input type="text" id="form-first" name="form-first" placeholder="First Name">
            </div>

            <div class="form-item-stacked">
                <label for="form-last">Last Name</label>
                <input type="text" id="form-last" name="form-last" placeholder="Last name">
            </div>

            <div class="form-item-stacked">
                <label for="form-email">Email</label>
                <input type="email" id="form-email" name="form-email" placeholder="Email" required>
            </div>

            <div class="form-item-stacked">
                <label for="form-pass">Password</label>
                <div class="password-wrapper">
                    <input type="password" id="form-pass" name="form-pass" placeholder="Password">
                    <span id="toggle-password" class="toggle-password">
                        <i id="pass-icon" class="fa-solid fa-eye"></i>
                    </span>
                </div>
            </div>

            <div class="centered">
                <button class="btn" id="actions" type="submit">Create account</button>
            </div>

            <div class="centered" style="margin-top: var(--spacing-md);">
                <button onclick="location.href = '../../index.php<?= $debugHandler->getDebugParam() ?>'" type="button" class="btn btn--outline">Back to login</button>
            </div>
        </form>
    </div>
</body>
<script type="module">
    // Set as module to allow imports
    import * as FormVerifier from "../../src/assets/js/form-verifs.js";

    // Get references to form's field
    let formFirst = document.getElementById("form-first");
    let formLast = document.getElementById("form-last");
    let formMail = document.getElementById("form-email");
    let formPass = document.getElementById("form-pass");

    // prevent multiple request when the infos are correct
    let canPress = true;

    // Get reference to form button
    let formButton = document.getElementById("actions");

    // Add click event on the previously got button
    formButton.addEventListener("click", (e) => {
        e.preventDefault();
        verifyForm();
    });

    function verifyForm(){
        let formValidation = true;
        // Clear previous highlights
        FormVerifier.resetFormState([formMail, formFirst, formLast, formPass]);

        // check each field with its corresponding checks
        formValidation &= FormVerifier.checkField(formFirst, formFirst, [FormVerifier.verifyEmptyness("Please enter your first name")]);
        formValidation &= FormVerifier.checkField(formLast, formLast, [FormVerifier.verifyEmptyness("Please enter your last name")]);
        formValidation &= FormVerifier.checkField(formMail, formMail, [FormVerifier.verifyEmptyness("Please enter your mail"), FormVerifier.verifyMail("Email is invalid") ]);
        formValidation &= FormVerifier.checkField(formPass, formPass, [FormVerifier.verifyEmptyness("Please enter a password"), FormVerifier.verifyLength("Password must be 10 characters long")]);

        // if everything checks out
        if(formValidation){
            canPress = false;
            if(<?= json_encode($debugHandler->isEnabled()) ?>){ // need json_encode due to php's false being an empty character
                FormVerifier.validateForm("Creating account ...");
                // force the submission of the form, without the preventDefault (keep the form validation logic)
                setTimeout(() => {
                    document.getElementById("creation-form").submit();
                }, 1500);
            }
            else{
                FormVerifier.validateForm("Creating account ...", "../../index.php<?= $debugHandler->getDebugParam() ?>");
            }
        }
    }

    const togglePassword = document.getElementById('toggle-password');

    togglePassword.addEventListener('click', function () {
        // Toggle the type of the field
        const type = formPass.getAttribute('type') === 'password' ? 'text' : 'password';
        formPass.setAttribute('type', type);

        // Toggle the icon
        const icon = document.getElementById('pass-icon');

        // switch between visual states
        icon.classList.toggle('fa-eye');
        icon.classList.toggle('fa-eye-slash');
    });
</script>
</html>