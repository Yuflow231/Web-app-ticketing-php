<?php

    session_start();

    require_once("../../assets/php/table-handler.php");
    require_once("../../assets/php/db-handler.php");
    require_once("../../assets/php/debug-handler.php");

    // Guard — kick back to login if not authenticated
    if (!isset($_SESSION['user'])) {
        header("Location: ../../../index.php?toast=not_logged_in");
        exit;
    }

    $debugHandler = DebugHandler::getInstance();
    $user = $_SESSION['user']; // shorthand for use in the page

    $ticket_id = $_GET["id"];
    // Fetch projects from DB based on role
    try {
        $db       = DBHandler::getInstance();
        $ticket = $db->getTicketById($ticket_id);
    } catch (Exception $e) {
        $ticket = [];
        $debugHandler->addInfoRight("DB Error", $e->getMessage());
    }

    $info =[
                "id" => "101",
                "title" => "Customizable UI bars",
                "project" => "Skyblocker",
                "description" => "Create modulable and customizable bars to replace the default bars of Hypixel Skyblock",
                "status" => "In Progress",
                "priority" => "Medium",
                "type" => "Included",
                "spent" => "4.5 Hours",
                "estimated" => "8.0 Hours",
                "collaborators" => [
                        "client1" => [
                                "pic" => "../../assets/images/icon.png",
                                "role" => "cat",
                                "first" => "VicIsCat",
                                "last" => "",
                                ]
                ]
        ]
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ticket details - Ticketing App</title>
    <link rel="stylesheet" href="../../assets/css/main.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/js/all.min.js" defer></script>
    <script type="module" src="../../assets/js/side-bar.js"></script>
</head>
<body>
    <!-- Navigation Bar -->
    <?php require_once("../../assets/php/side-nav-component.php"); ?>

    <!-- Page content -->
    <main class="main-content">
        <header class="page-header">
            <h1>Ticket #<?= $ticket["id"] ?>: <?= $ticket["name"] ?></h1>
        </header>

        <div class="detail-container">
            <section class="detail-card">
                <div class="detail-item">
                    <label>Title</label>
                    <h2><?= $ticket["name"] ?></h2>
                </div>

                <div class="detail-item" >
                    <label>Associated project</label>
                    <p><?= $db->getProjectNameByTicketId($ticket["project_id"]) ?></p>
                </div>

                <div class="detail-item" >
                    <label>Detailed Description</label>
                    <p><?= $ticket["description"] ?></p>
                </div>

                <div class="inline-elements">
                    <div class="detail-item">
                        <label>Actual Time Spent</label>
                        <p><?= $ticket["estimated_time"] ?></p>
                    </div>
                    <div class="detail-item">
                        <label>Estimated Time</label>
                        <p><?= $ticket["estimated_time"] ?></p>
                    </div>
                </div>

                <div class="inline-elements" style="margin-top: auto; padding-top: 1rem;">
                    <button class="btn">Update Ticket</button>
                    <button class="btn btn--danger">Close Ticket</button>
                </div>
            </section>

            <div class="detail-side">
                <section class="detail-card">
                    <h2>Classification</h2>
                    <div class="detail-item">
                        <label>Status</label>
                        <span class="badge <?php setBadgeColor($ticket["status"]) ?>"><?= $ticket["status"] ?></span>
                    </div>
                    <div class="detail-item">
                        <label>Priority</label>
                        <span class="badge <?php setBadgeColor($ticket["priority"]) ?>"><?= $ticket["priority"] ?></span>
                    </div>
                    <div class="detail-item">
                        <label>Type</label>
                        <span class="badge <?php setBadgeColor($ticket["type"]) ?>"><?= $ticket["type"] ?></span>
                    </div>
                </section>

                <section class="detail-card">
                    <h2>Assigned Collaborators</h2>
                    <div id="collaborator-list">
                        <?php foreach ($db->getTicketWorkers($ticket["id"]) as $contributor): ?>
                        <div class="user-profile-inline" style="margin-bottom: var(--spacing-sm);">
                            <img src="../../../src/assets/images/<?= $contributor["profile_pic"] ?> ?>" alt="User Profile" class="profile-pic" >
                            <div class="item-stacked" style="margin-left: var(--spacing-sm);">
                                <div>
                                    <span class="username" data-type="first-name"><?= $contributor["first_name"] ?></span>
                                    <span class="username" data-type="last-name"><?= $contributor["last_name"] ?></span>
                                </div>
                                <span class="user-role"><?= $contributor["role"] ?></span>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </section>
            </div>

            <div class="detail-card full-width" id="file-list">
                <h2>Files associated</h2>
                <button class="btn" style="margin-bottom: var(--spacing-sm)">Edit documents</button>
                <ul>
                    <li>Visual Examples</li>
                    <li>Visual Examples</li>
                </ul>
            </div>
        </div>
    </main>
</body>
</html>