<?php
    require_once("../assets/php/debug-handler.php");
    require_once("../assets/php/db-handler.php");

    // Initialize debug handler
    $debugHandler = DebugHandler::getInstance();
    $debugHandler->addInfoLeft('Test', 'create-account');
    $debugHandler->addPostParams();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $firstName = trim($_POST['form-first']  ?? '');
        $lastName  = trim($_POST['form-last']   ?? '');
        $email     = trim($_POST['form-email']  ?? '');
        $password  =      $_POST['form-pass']   ?? '';

        try {
            $db = DBHandler::getInstance();

            if ($db->emailExists($email)) {
                header("Location: ./create-account.php?toast=email_taken" . $debugHandler->getDebugParam());
                exit;
            } else {
                $userId = $db->createUser($firstName, $lastName, $email, $password);
                header("Location: ../../index.php?toast=account_created" . $debugHandler->getDebugParam());
                exit;
            }
        } catch (Exception $e) {
            header("Location: ./create-account.php?toast=db_error" . $debugHandler->getDebugParam());
            exit;
        }
    }
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
    import * as FormVerifier from "../../src/assets/js/form-verifs.js";
    import Toast from "../../src/assets/js/toast.js";

    // ── Toast from PHP redirect ──────────────────────────────
    const toastMessages = {
        email_taken: { text: "This email is already in use.",  type: "error" },
        db_error:    { text: "A database error occurred.",     type: "error" },
    };

    const params   = new URLSearchParams(window.location.search);
    const toastKey = params.get('toast');

    if (toastKey && toastMessages[toastKey]) {
        const { text, type } = toastMessages[toastKey];
        Toast(text, type);
        // Clean the ?toast param from the URL without reloading the page
        const cleanUrl = new URL(window.location.href);
        cleanUrl.searchParams.delete('toast');
        window.history.replaceState({}, '', cleanUrl);
    }

    // ── Form logic ───────────────────────────────────────────
    let formFirst = document.getElementById("form-first");
    let formLast  = document.getElementById("form-last");
    let formMail  = document.getElementById("form-email");
    let formPass  = document.getElementById("form-pass");

    let canPress = true;

    let formButton = document.getElementById("actions");
    formButton.addEventListener("click", (e) => {
        e.preventDefault();
        verifyForm();
    });

    function verifyForm(){
        let formValidation = true;
        FormVerifier.resetFormState([formMail, formFirst, formLast, formPass]);

        formValidation &= FormVerifier.checkField(formFirst, formFirst, [FormVerifier.verifyEmptyness("Please enter your first name")]);
        formValidation &= FormVerifier.checkField(formLast,  formLast,  [FormVerifier.verifyEmptyness("Please enter your last name")]);
        formValidation &= FormVerifier.checkField(formMail,  formMail,  [FormVerifier.verifyEmptyness("Please enter your mail"), FormVerifier.verifyMail("Email is invalid")]);
        formValidation &= FormVerifier.checkField(formPass,  formPass,  [FormVerifier.verifyEmptyness("Please enter a password"), FormVerifier.verifyLength("Password must be 10 characters long")]);

        if(formValidation){
            canPress = false;

            FormVerifier.validateForm("Creating account ...");
            setTimeout(() => {
                document.getElementById("creation-form").submit();
            }, 1500);
        }
    }

    const togglePassword = document.getElementById('toggle-password');
    togglePassword.addEventListener('click', function () {
        const type = formPass.getAttribute('type') === 'password' ? 'text' : 'password';
        formPass.setAttribute('type', type);
        const icon = document.getElementById('pass-icon');
        icon.classList.toggle('fa-eye');
        icon.classList.toggle('fa-eye-slash');
    });
</script>
</html>