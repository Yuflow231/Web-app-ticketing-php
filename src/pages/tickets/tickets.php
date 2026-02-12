<?php
    // Import debug handler
    require_once("../../assets/php/debug-handler.php");
    require_once("../../assets/php/table-handler.php");
    $debugHandler = DebugHandler::getInstance();

    $tableRows =[
            [
                    "id" => "101",
                    "title" => "Customizable UI bars",
                    "project" => "Skyblocker",
                    "status" => "In Progress",
                    "priority" => "Medium",
                    "clients" => [
                            "client1" => "../../assets/images/icon.png",
                    ]
            ],
            [
                    "id" => "102",
                    "title" => "Fix Render for 1.21.11",
                    "project" => "Customizable Player Model",
                    "status" => "On Hold",
                    "priority" => "High",
                    "clients" => [
                            "client1" => "../../assets/images/icon.png",
                            "client2" => "../../assets/images/icon.png",
                    ]
            ],
            [
                    "id" => "105",
                    "title" => "Implement Dark Mode",
                    "project" => "Skyblocker",
                    "status" => "New",
                    "priority" => "Low",
                    "clients" => [
                            "client1" => "../../assets/images/icon.png",
                    ]
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
    <title>Tickets - Ticketing App</title>
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
                <h2>My tickets</h2>
                <a href="./ticket-creation.php<?= $debugHandler->getDebugParam() ?>">
                    <button type="button" class="btn">
                        <i class="fa-solid fa-plus"></i>
                        Create ticket
                    </button>
                </a>
            </div>
            <div class="page-header-line">
                <div style="display: flex; gap: var(--spacing-lg); flex-wrap: wrap;">
                    <div class="filter">
                        <label for="status">Status</label>
                        <select id="status" name="status">
                            <option value="All" <?php isSelected("status","All") ?>>All</option>
                            <option value="Open" <?php isSelected("status","New") ?>>New</option>
                            <option value="In Progress" <?php isSelected("status","In Progress") ?>>In Progress</option>
                            <option value="On Hold" <?php isSelected("status","On Hold") ?>>On Hold</option>
                            <option value="Completed" <?php isSelected("status","Completed") ?>>Completed</option>
                            <option value="Closed" <?php isSelected("status","Closed") ?>>Closed</option>
                        </select>
                    </div>
                    <div class="filter">
                        <label for="priority">Priority</label>
                        <select id="priority" name="priority">
                            <option value="All" <?php isSelected("priority","All") ?> >All</option>
                            <option value="High" <?php isSelected("priority","High") ?>>High</option>
                            <option value="Medium" <?php isSelected("priority","Medium") ?>>Medium</option>
                            <option value="Low" <?php isSelected("priority","Low") ?>>Low</option>
                        </select>
                    </div>
                </div>
                <div class="filter">
                    <label for="search">Research</label>
                    <input type="text" id="search" name="search" placeholder="Research a ticket">
                </div>
            </div>
            <div>
                <button class="btn"><i class="fa-solid fa-angle-left"></i></button>
                <span>Page 1 of 1</span>
                <button class="btn"><i class="fa-solid fa-angle-right"></i></button>
            </div>
        </header>

        <!-- Tickets Section -->
        <section class="tickets">
            <div class="table-card">
                <table>
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Title</th>
                        <th>Project</th>
                        <th>Status</th>
                        <th>Priority</th>
                        <th>Assigned</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody id="tickets-tbody">
                    <!-- Tickets will be loaded here -->
                    <?php foreach ($tableRows as $ticket) : ?>
                        <?php if (isFiltered($ticket, ['status', 'priority'])): ?>
                        <tr>
                            <td data-label="ID">#<?= $ticket["id"] ?></td>
                            <td data-label="Title"><strong><?= $ticket["title"] ?></strong></td>
                            <td data-label="Project"><?= $ticket["project"] ?></td>
                            <td data-label="Status"><span class="badge  <?php setBadgeColor($ticket["status"]) ?> "><?= $ticket["status"] ?></span></td>
                            <td data-label="Priority"><span class="badge  <?php setBadgeColor($ticket["priority"]) ?> "><?= $ticket["priority"] ?></span></td>
                            <td data-label="Assigned">
                                <div class="avatar-line">
                                    <?php foreach ($ticket["clients"] as $clientPP) : ?>
                                    <img src="<?= $clientPP ?>" title="Unassigned" alt="profile-picture" class="profile-pic-mini">
                                    <?php endforeach; ?>
                                </div>
                            </td>
                            <td data-label="Actions"><a href="./ticket-details.php<?=  $debugHandler->getDebugParam() ?>" class="icon"><i class="fa-solid fa-arrow-up-right-from-square"></i></a></td>
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
    // Initialize for tickets table
    new TableManager('.tickets table', 5);
    console.log("The current language is", LangHandler.getLanguage());
</script>
</html>