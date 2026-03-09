<?php
session_start();
require_once("./src/assets/php/debug-handler.php");
require_once("./src/assets/php/db-handler.php");

// Initialize debug handler
$debugHandler = DebugHandler::getInstance();
$debugHandler->addInfoLeft('Test', 'login');
$debugHandler->addPostParams();
$debugHandler->addGetParams();
$debugHandler->addForwardedParams(); // show POST data forwarded from a previous redirect
$debugHandler->addSessionInfo();     // show session contents if any

// Already logged in? Skip the login page entirely
if (isset($_SESSION['user'])) {
    header("Location: ./src/pages/dashBoard.php" . $debugHandler->getDebugAppend());
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = trim($_POST['email']    ?? '');
    $password =      $_POST['password'] ?? '';

    try {
        $db   = DBHandler::getInstance();
        $user = $db->loginUser($email, $password);

        if ($user) {
            $_SESSION['user'] = $user;
            header("Location: ./src/pages/dashBoard.php?toast=login_success" . $debugHandler->getDebugAppend());
            exit;
        } else {
            header("Location: ./index.php?toast=invalid_credentials" . $debugHandler->getDebugAppend());
            exit;
        }
    } catch (Exception $e) {
        header("Location: ./index.php?toast=db_error" . $debugHandler->getDebugAppend());
        exit;
    }
}
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

        <div style="display: flex;flex-direction: column">
            <button class="btn centered" id="actions" type="submit">
                Login
            </button>
            <button onclick="location.href = './src/pages/create-account.php<?= $debugHandler->getDebugParam() ?>'" class="btn centered btn--outline" type="button" style="margin-top: var(--spacing-md);">
                Create account
            </button>
        </div>
    </form>
</div>

</body>
<script type="module">
    // Set as module to allow imports
    import * as FormVerifier from "./src/assets/js/form-verifs.js";
    import * as LangHandler from "./src/assets/js/language-handler.js";import Toast from "./src/assets/js/toast.js";

    const toastMessages = {
        account_created: { text: "Account has been created !", type: "success" },
        invalid_credentials:{ text: "Invalid email or password.",       type: "error"   },
        db_error:           { text: "A database error occurred.",       type: "error"   },
        logged_out:         { text: "You have been logged out.",        type: "neutral" },
    };

    const params   = new URLSearchParams(window.location.search);
    const toastKey = params.get('toast');

    if (toastKey && toastMessages[toastKey]) {
        const { text, type } = toastMessages[toastKey];
        Toast(text, type);


        const cleanUrl = new URL(window.location.href);
        cleanUrl.searchParams.delete('toast');
        window.history.replaceState({}, '', cleanUrl);
    }
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

            // Always submit — PHP handles the redirect to dashboard
            setTimeout(() => {
                document.getElementById("login-form").submit();
            }, 1500);
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