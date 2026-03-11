<?php
    session_start();
    require_once("../assets/php/debug-handler.php");

    // Guard — kick back to login if not authenticated
    if (!isset($_SESSION['user'])) {
        header("Location: ../../index.php?toast=not_logged_in");
        exit;
    }

    $debugHandler = DebugHandler::getInstance();
    $user = $_SESSION['user']; // shorthand for use in the page

    function isAdmin(string $role):bool
    {
        $roleLower = strtolower($role);

        return $roleLower === "administrator";
    }

    $temp = 0;
    function isLanguageSet(string $lang, string $compare): void{
        global  $debugHandler, $temp;

        $debugHandler->addInfoRight("lang".$temp, $lang);
        $debugHandler->addInfoRight("compare".$temp, $compare);
        $debugHandler->addInfoRight("lang === compare".$temp, $lang === $compare);

        if ($lang === $compare){
            echo("selected");
            $debugHandler->addInfoRight("yup".$temp, "yup");
        }

        $temp++;
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile - Ticketing App</title>
    <link rel="stylesheet" href="../../src/assets/css/main.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/js/all.min.js" defer></script>
    <script type="module" src="../../src/assets/js/side-bar.js"></script>
</head>
<body>
    <?php
        // Import debug handler
        require_once("../assets/php/debug-handler.php");
        $debugHandler = DebugHandler::getInstance();

        // Add page-specific debug info
        $debugHandler->addInfoRight('Test', 'Additional ');
    ?>

    <!-- Navigation Bar -->
    <?php require_once("../assets/php/side-nav-component.php"); ?>

    <!-- Page content -->
    <main class="main-content">
        <header class="page-header">
            <h1>User Profile</h1>
        </header>

        <div class="detail-container">
            <section class="detail-card">
                <header class="profile-header">
                    <div class="name-group">
                        <div class="username" data-type="first-name"><?= $user["first_name"] ?></div>
                        <div class="username" data-type="last-name"><?= $user["last_name"] ?></div>
                        <p class="user-role"><?= $user["role"] ?></p>
                    </div>


                    <img src="../../src/assets/images/<?= $user["profile_pic"] ?>" alt="User Profile" class="profile-pic" >
                </header>

                <div>
                    <div class="detail-item">
                        <label>Email Address</label>
                        <p><?= $user["email"] ?></p>
                    </div>
                    <div class="detail-item">
                        <label>Member Since</label>
                        <p><?= $user["join_date"] ?></p>
                    </div>
                    <div>
                        <button type="button" class="btn">Edit</button>
                    </div>
                </div>
            </section>

            <div class="detail-side">
                <section class="detail-card">
                    <h2>Preferences</h2>
                    <div class="form-item" style="width: 10rem;">
                        <label for="language-select">Language</label>
                        <select id="language-select">
                            <option value="en" <?php isLanguageSet($user["language"], "en") ?>>English</option>
                            <option value="fr" <?php isLanguageSet($user["language"], "fr") ?>>French</option>
                        </select>
                    </div>
                    <?php if(isAdmin($user["role"])): ?>
                    <div class="form-item-stacked">
                        <label for="debug">Debug mode</label>
                        <input type="checkbox" id="debug">
                    </div>
                    <?php endif; ?>
                </section>

                <section class="detail-card">
                    <h2>Security</h2>
                    <div class="detail-item">
                        <label>Password</label>
                        <p style="margin-bottom: 1rem;">••••••••••••</p>
                        <a href="./reset-password.php<?= $debugHandler->getDebugParam() ?>" class="password" style="">Change Password</a>
                    </div>
                </section>
            </div>
        </div>
    </main>
</body>
<script type="module">
    import * as LangHandler from "../../src/assets/js/language-handler.js";
    console.log("The current language is", LangHandler.getLanguage());

    const languageSelect = document.getElementById("language-select");
    const debugCheck = document.getElementById("debug");

    window.addEventListener("DOMContentLoaded", () => {
        languageSelect.value = LangHandler.getLanguage();

        debugCheck.checked = (<?= json_encode($debugHandler->isEnabled()) ?>) === true;
    });

    languageSelect.addEventListener("change", function() {
        LangHandler.setLanguage(this.value);
        console.log("The current language is", LangHandler.getLanguage());
    });

    debugCheck.addEventListener("change", function() {
        const debugEnabled = debugCheck.checked;

        // Reload the page with or without the parameter
        const currentUrl = new URL(window.location.href);
        if (debugEnabled) {
            currentUrl.searchParams.set('debug', '1');
        } else {
            currentUrl.searchParams.delete('debug');
        }
        window.location.href = currentUrl.toString();
    });
</script>
</html>