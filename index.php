<?php
    require_once("./src/assets/php/debug-handler.php");
    // Initialize debug handler
    $debugHandler = DebugHandler::getInstance();

    $debugHandler->addInfoLeft('Test', 'login');

    $debugHandler->addPostParams();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Ticketing App</title>
    <link rel="stylesheet" href="./src/assets/css/main.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/js/all.min.js" defer></script>
</head>
<body>
    <div id="login-box">
        <form id="login-form" class="form-content card-wo-hover" method="POST">
            <h2 style="text-align: center">Login</h2>

            <div class="form-item-stacked">
                <label for="form-email">Email</label>
                <input type="email" id="form-email" name="email" placeholder="Email">
            </div>

            <div class="form-item-stacked">
                <label for="form-password">Password</label>
                <div class="password-wrapper">
                    <input type="password" id="form-password" name="password" placeholder="Password">
                    <span id="toggle-password" class="toggle-password">
                        <i id="pass-icon" class="fa-solid fa-eye"></i>
                    </span>
                </div>
            </div>

            <div class="password">
                <a href="./src/pages/reset-password.php<?= $debugHandler->getDebugParam() ?>">Forgot password ?</a>
            </div>

            <div class="centered">
                <button class="btn" id="actions" type="submit">
                    Login
                </button>
            </div>
        </form>
    </div>

</body>
<script type="module">
    // Set as module to allow imports
    import * as FormVerifier from "./src/assets/js/form-verifs.js";
    import * as LangHandler from "./src/assets/js/language-handler.js";
    console.log("The current language is", LangHandler.getLanguage());


    // get reference to form's fields
    const formMail = document.getElementById("form-email");
    const formPass = document.getElementById("form-password");

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
        FormVerifier.resetFormState([formMail, formPass]);

        // check each field with its corresponding checks
        formValidation &= FormVerifier.checkField(formMail, formMail, [FormVerifier.verifyEmptyness("Please enter your mail"), FormVerifier.verifyMail("Email is invalid") ]);
        formValidation &= FormVerifier.checkField(formPass, formPass, [FormVerifier.verifyEmptyness("Please enter your password")]);

        // if everything checks out
        if(formValidation){
            canPress = false;


            if(<?= json_encode($debugHandler->isEnabled()) ?>){ // need json_encode due to php's false being an empty character
                FormVerifier.validateForm("Connecting ...");
                // force the submission of the form, without the preventDefault (keep the form validation logic)
                setTimeout(() => {
                    document.getElementById("login-form").submit();
                }, 1500);
            }
            else{
                FormVerifier.validateForm("Connecting ...", "./src/pages/dashBoard.php<?= $debugHandler->getDebugParam() ?>");
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