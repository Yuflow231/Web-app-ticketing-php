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
        $debugHandler->addInfo('Test', 'Additional ');

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
                        <div class="username" data-type="first-name">User</div>
                        <div class="username" data-type="last-name">Name</div>
                        <p class="user-role">Administrator</p>
                    </div>
                    <!-- placeholder using my YouTube profile pic -->
                    <img src="https://yt3.ggpht.com/pz97Hxe-gW4DR1-S4HmoZopwKXppAHPajMDtCaSSM-3HNV31wECJmegkZAohyEh7qAbCNQAHUg=s176-c-k-c0x00ffffff-no-rj" alt="User Profile" class="profile-pic" >
                </header>

                <div>
                    <div class="detail-item">
                        <label>Email Address</label>
                        <p>yuflow.uwu@example.com</p>
                    </div>
                    <div class="detail-item">
                        <label>Member Since</label>
                        <p>January 20, 2024</p>
                    </div>
                    <div class="detail-item">
                        <label>Location</label>
                        <p>New York, USA</p>
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
                            <option value="en">English</option>
                            <option value="fr">French</option>
                        </select>
                    </div>
                    <div class="form-item-stacked">
                        <label for="debug">Debug mode</label>
                        <input type="checkbox" id="debug">
                    </div>
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

        debugCheck.checked = localStorage.getItem("debug") === "true";
    });

    languageSelect.addEventListener("change", function() {
        LangHandler.setLanguage(this.value);
        console.log("The current language is", LangHandler.getLanguage());
    });

    debugCheck.addEventListener("change", function() {
        const debugEnabled = debugCheck.checked;
        localStorage.setItem("debug", debugEnabled);

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