<?php
    session_start();
    require_once("../../assets/php/table-handler.php");
    require_once("../../assets/php/debug-handler.php");
    require_once("../../assets/php/db-handler.php");

    // Guard — kick back to login if not authenticated
    if (!isset($_SESSION['user'])) {
        header("Location: ../../../index.php?toast=not_logged_in");
        exit;
    }

    $debugHandler = DebugHandler::getInstance();
    $user = $_SESSION['user']; // shorthand for use in the page

    $project_id = $_GET["id"];
    // Fetch projects from DB based on role
    try {
        $db       = DBHandler::getInstance();
        $project = $db->getProjectById($project_id);
    } catch (Exception $e) {
        $project = [];
        $debugHandler->addInfoRight("DB Error", $e->getMessage());
    }

    $info =[
            "id" => "101",
            "title" => "Skyblocker",
            "status" => "In Progress",
            "end" => "24/10/2026",
            "description" => "Create a minecraft mode that act as an add-on for the Hypixel server, more precisely for its Skyblock game mode. Its role is to enhance the game experience by providing quality of life improvement, as well as improving guidance.",
            "spent" => "45.5 Hours",
            "estimated" => "120 Hours",
            "progress" => "38%",
            "collaborators" => [
                    "client1" => [
                            "pic" => "../../assets/images/icon.png",
                            "role" => "Maintainer",
                            "first" => "Alex33856",
                            "last" => "",
                    ],
                    "client2" => [
                            "pic" => "../../assets/images/icon.png",
                            "role" => "cat",
                            "first" => "VicIsCat",
                            "last" => "",
                    ]
            ]
    ];

    $tableRows =[
            [
                    "id" => "101",
                    "title" => "Customizable UI bars",
                    "status" => "In Progress",
                    "priority" => "Medium",
                    "type" => "Included"
            ],
            [
                    "id" => "105",
                    "title" => "Implement Dark Mode",
                    "status" => "New",
                    "priority" => "Low",
                    "type" => "Billed"
            ]
    ];

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Project details - Ticketing App</title>
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
            <h1 id="project-title-header">Project: <?= $project["name"] ?></h1>
        </header>

        <div class="detail-container" id="project-data-container">
            <section class="detail-card">
                <div class="detail-item">
                    <label>ID</label>
                    <p id="project-id">
                        #<?= $project["id"] ?>
                    </p>
                </div>
                <div class="inline-elements">
                    <div class="detail-item">
                        <label>Status</label>
                        <span class="badge <?php setBadgeColor($project["status"]) ?>"><?= $project["status"] ?></span>
                    </div>
                    <div class="detail-item">
                        <label>Closing date</label>
                        <p id="closing-date">
                            <?= $project["closing_date"] ?>
                        </p>
                    </div>
                </div>

                <div class="detail-item">
                    <label>Detailed Description</label>
                    <p id="project-description">
                        <?= $project["description"] ?>
                    </p>
                </div>

                <div class="inline-elements" style="margin-top: 2rem;">
                    <div class="detail-item">
                        <label>Actual Time Spent</label>
                        <p id="actual-time"><?= $project["estimated_time"] ?> hours</p>
                    </div>
                    <div class="detail-item">
                        <label>Estimated Time</label>
                        <p id="est-time"><?= $project["estimated_time"] ?> hours</p>
                    </div>
                </div>


                <div class="inline-elements" style="margin-top: auto; padding-top: 1rem;">
                    <button class="btn">Update Project</button>
                    <button class="btn btn--danger">Close Project</button>
                </div>
            </section>

            <div class="detail-side">
                <section class="detail-card">
                    <h2>Project Team</h2>
                    <div id="collaborator-list">
                        <?php foreach ( $db->getProjectTeam($project_id) as $user): ?>
                        <div class="user-profile-inline" style="margin-bottom: var(--spacing-sm);" >
                            <img src="../../../src/assets/images/<?= $user["profile_pic"] ?>" alt="User Profile" class="profile-pic" >
                            <div class="item-stacked" style="margin-left: var(--spacing-sm);">
                                <div>
                                    <span class="username" data-type="first-name"><?= $user["first_name"] ?></span>
                                    <span class="username" data-type="last-name"><?= $user["last_name"] ?></span>
                                </div>
                                <span class="user-role"><?= $user["role"] ?></span>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </section>

                <section class="detail-card">
                    <h2>Statistics</h2>
                    <div class="detail-item">
                        <label>Completion</label>
                        <div class="progress-bar">
                            <div class="progress-fill" style="width: <?= $project["progress_percent"] ?>%;"></div>
                        </div>
                        <p style="font-size: var(--font-size-sm); margin-top: var(--spacing-sm);"><?= $project["progress_percent"] ?>% of ticket completion</p>
                    </div>
                </section>
            </div>
            <section class="detail-card full-width">
                <h2>Linked Tickets</h2>
                <div class="filter">
                    <label for="type">Type</label>
                    <select id="type" name="type">
                        <option value="All" <?php isSelected("type","All") ?> >All</option>
                        <option value="Included" <?php isSelected("type","Included") ?>>Included</option>
                        <option value="Billed" <?php isSelected("type","Billed") ?>>Billed</option>
                    </select>
                </div>
                <div style="margin: var(--spacing-sm) 0;">
                    <button class="btn"><i class="fa-solid fa-angle-left"></i></button>
                    <span>Page 1 of 1</span>
                    <button class="btn"><i class="fa-solid fa-angle-right"></i></button>
                </div>
                <div class="table-card" style="margin-top: 0.5rem;">
                    <table id="table" style="width: 100%; font-size: 0.9rem;">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>Ticket Title</th>
                            <th>Status</th>
                            <th>Priority</th>
                            <th>Type</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($db->getTicketsByProject($project_id) as $ticket): ?>
                            <?php if (isFiltered($ticket, ['type'])): ?>
                            <tr>
                                <td data-label="ID">#<?= $ticket["id"] ?></td>
                                <td data-label="Title"><strong><?= $ticket["name"] ?></strong></td>
                                <td data-label="Status"><span class="badge <?php setBadgeColor($ticket["status"]) ?>"><?= $ticket["status"] ?></span></td>
                                <td data-label="Priority"><span class="badge <?php setBadgeColor($ticket["priority"]) ?>"><?= $ticket["priority"] ?></span></td>
                                <td data-label="Type"><span class="badge <?php setBadgeColor($ticket["type"]) ?>"><?= $ticket["type"] ?></span></td>
                                <td data-label="Action"><a href="../tickets/ticket-details.php?id=<?= $ticket["id"] ?><?=  $debugHandler->getDebugAppend() ?>" class="icon"><i class="fa-solid fa-arrow-up-right-from-square"></i></a></td>
                            </tr>
                            <?php endif; ?>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </section>

            <div class="detail-card full-width" id="file-list">
                <h2>Files associated</h2>
                <button class="btn" style="margin-bottom: var(--spacing-sm)">Edit documents</button>
                <ul>
                    <li>Business Contract</li>
                    <li>User stories</li>
                </ul>
            </div>
        </div>
    </main>
</body>
<script type="module">
    import { TableManager } from "../../assets/js/table-handler.js";
    // Initialize for tickets table
    new TableManager('.table-card table', 5);
</script>
</html>