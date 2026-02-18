<?php
    // Import debug handler
    require_once("../../assets/php/debug-handler.php");
    require_once("../../assets/php/table-handler.php");

    $debugHandler = DebugHandler::getInstance();

    $tableRows  = [
            [
                    "id" => "101",
                    "name" => "Skyblocker",
                    "client" => [
                            "ppRef" => "../../assets/images/icon.png",
                            "name" => "VicIsACat",
                    ],
                    "status" => "In progress",
                    "progress" => "3%",
                    "date" => "52/02/1996",
            ],
            [
                    "id" => "102",
                    "name" => "Customizable Player Model",
                    "client" => [
                            "ppRef" => "../../assets/images/icon.png",
                            "name" => "tom54541",
                    ],
                    "status" => "On Hold",
                    "progress" => "75%",
                    "date" => "30/01/2026",
            ],
            [
                    "id" => "103",
                    "name" => "Customizable Player Model",
                    "client" => [
                            "ppRef" => "../../assets/images/icon.png",
                            "name" => "tom54541",
                    ],
                    "status" => "New",
                    "progress" => "75%",
                    "date" => "30/01/2026",
            ],
            [
                    "id" => "104",
                    "name" => "Customizable Player Model",
                    "client" => [
                            "ppRef" => "../../assets/images/icon.png",
                            "name" => "tom54541",
                    ],
                    "status" => "Completed",
                    "progress" => "25%",
                    "date" => "30/01/2026",
            ],
            [
                    "id" => "105",
                    "name" => "Customizable Player Model",
                    "client" => [
                            "ppRef" => "../../assets/images/icon.png",
                            "name" => "tom54541",
                    ],
                    "status" => "Closed",
                    "progress" => "86%",
                    "date" => "30/01/2026",
            ],
            [
                    "id" => "106",
                    "name" => "Customizable Player Model",
                    "client" => [
                            "ppRef" => "../../assets/images/icon.png",
                            "name" => "tom54541",
                    ],
                    "status" => "Closed",
                    "progress" => "75%",
                    "date" => "30/01/2026",
            ],
            [
                    "id" => "107",
                    "name" => "Customizable Player Model",
                    "client" => [
                            "ppRef" => "../../assets/images/icon.png",
                            "name" => "tom54541",
                    ],
                    "status" => "In Progress",
                    "progress" => "86%",
                    "date" => "30/01/2026",
            ]
    ];

    $debugHandler->addGetParams();
    $debugHandler->addSeparatorRight();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Projects - Ticketing App</title>
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
            <div class="page-header-line">
                <h2>My projects</h2>
                <a href="./project-creation.php<?=  $debugHandler->getDebugParam() ?>">
                    <button type="button" class="btn">
                        <i class="fa-solid fa-plus"></i>
                        Create project
                    </button>
                </a>
            </div>
            <div class="page-header-line">
                <div class="filter">
                    <label for="status">Status</label>
                    <select id="status" name="status">
                        <option value="All" <?php isSelected("status","All") ?> >All</option>
                        <option value="New" <?php isSelected("status","New") ?>>New</option>
                        <option value="In Progress" <?php isSelected("status","In Progress") ?>>In Progress</option>
                        <option value="On Hold" <?php isSelected("status","On Hold") ?>>On Hold</option>
                        <option value="Completed" <?php isSelected("status","Completed") ?>>Completed</option>
                        <option value="Closed" <?php isSelected("status","Closed") ?>>Closed</option>
                    </select>
                </div>
                <div class="filter">
                    <label for="search">Research</label>
                    <input type="text" id="search" name="search" placeholder="Research a project"">
                </div>
            </div>
            <div>
                <button class="btn"><i class="fa-solid fa-angle-left"></i></button>
                <span>Page 1 of 1</span>
                <button class="btn"><i class="fa-solid fa-angle-right"></i></button>
            </div>
        </header>

        <!-- Projects section -->
        <section class="project">
            <div class="table-card">
                <table id="table">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Project name</th>
                        <th>Client</th>
                        <th>Status</th>
                        <th>Progress</th>
                        <th>Creation date</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    <!-- Projects will be loaded here -->
                    <!-- Project template -->
                    <?php foreach ($tableRows  as $project): ?>
                        <?php if (isFiltered($project, ['status'])): ?>
                        <tr>
                            <td data-label="ID">#<?= $project["id"] ?></td>
                            <td data-label="Project name"><strong><?= $project["name"] ?></strong></td>
                            <td data-label="Client">
                                <div class="user-profile-inline">
                                    <img src="<?= $project["client"]["ppRef"] ?>" class="profile-pic" alt="profile-picture" style="width:40px; height:40px;">
                                    <span style="margin-left: var(--spacing-sm)"><?= $project["client"]["name"] ?></span>
                                </div>
                            </td>
                            <td data-label="Status">
                                <span class="badge <?php setBadgeColor($project["status"]) ?> "><?= $project["status"] ?></span>
                            </td>
                            <td data-label="Progress">
                                <div class="progress-container">
                                    <div class="progress-bar">
                                        <div class="progress-fill" style="width: <?= $project["progress"] ?>;"></div>
                                    </div>
                                    <div class="progress-percentage"><?= $project["progress"] ?></div>
                                </div>
                            </td>
                            <td data-label="Creation date"><?= $project["date"] ?></td>
                            <td data-label="Actions"><a href="./project-details.php<?=  $debugHandler->getDebugParam() ?>" class="icon"><i class="fa-solid fa-arrow-up-right-from-square"></i></a></td>
                        </tr>
                        <?php endif; ?>
                    <?php addCount(); endforeach; ?>
                    </tbody>
                </table>
            </div>
        </section>

        <footer class="page-footer">
            <div>
                <button class="btn"><i class="fa-solid fa-angle-left"></i></button>
                <span>Page 1 of 1</span>
                <button class="btn"><i class="fa-solid fa-angle-right"></i></button>
            </div>
        </footer>
    </main>
</body>
<script type="module">
    import * as LangHandler from "../../assets/js/language-handler.js";
    import { TableManager } from "../../assets/js/table-handler.js";

    // Initialize for projects table (using 5 rows per page)
    new TableManager('.project table', 5);
    console.log("The current language is", LangHandler.getLanguage());
</script>
</html>