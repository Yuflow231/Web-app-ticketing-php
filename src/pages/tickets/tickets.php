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
                <a href="./ticket-creation.php<?= $debug ?>">
                    <button type="button" class="btn">
                        <i class="fa-solid fa-plus"></i>
                        Create ticket
                    </button>
                </a>
            </div>
            <div class="page-header-line">
                <div style="display: flex; gap: var(--spacing-lg); flex-wrap: wrap;">
                    <div class="filter">
                        <label for="priority">Priority</label>
                        <select id="priority" name="priority">
                            <option value="">All</option>
                            <option value="High">High</option>
                            <option value="Medium">Medium</option>
                            <option value="Low">Low</option>
                        </select>
                    </div>
                    <div class="filter">
                        <label for="status">Status</label>
                        <select id="status" name="status">
                            <option value="">All</option>
                            <option value="Open">New</option>
                            <option value="In Progress">In Progress</option>
                            <option value="On Hold">On Hold</option>
                            <option value="Closed">Closed</option>
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
                        <tr>
                            <td data-label="ID">#101</td>
                            <td data-label="Title"><strong>Customizable UI bars</strong></td>
                            <td data-label="Project">Skyblocker</td>
                            <td data-label="Status"><span class="badge green">In Progress</span></td>
                            <td data-label="Priority"><span class="badge orange">Medium</span></td>
                            <td data-label="Assigned">
                                <div class="avatar-line">
                                    <img src="../../assets/images/icon.png" title="Unassigned" alt="profile-picture" class="profile-pic-mini">
                                </div>
                            </td>
                            <td data-label="Actions"><a href="./ticket-details.php<?= $debug ?>" class="icon"><i class="fa-solid fa-arrow-up-right-from-square"></i></a></td>
                        </tr>
                        <tr>
                            <td data-label="ID">#102</td>
                            <td data-label="Title"><strong>Fix Render for 1.21.11</strong></td>
                            <td data-label="Project">Customizable Player Model</td>
                            <td data-label="Status"><span class="badge orange">On Hold</span></td>
                            <td data-label="Priority"><span class="badge red">High</span></td>
                            <td data-label="Assigned">
                                <div class="avatar-line">
                                    <img src="../../assets/images/icon.png" title="Unassigned" alt="profile-picture" class="profile-pic-mini">
                                    <img src="../../assets/images/icon.png" title="Unassigned" alt="profile-picture" class="profile-pic-mini">
                                </div>
                            </td>
                            <td data-label="Actions"><a href="./ticket-details.php<?= $debug ?>" class="icon"><i class="fa-solid fa-arrow-up-right-from-square"></i></a></td>
                        </tr>
                        <tr>
                            <td data-label="ID">#105</td>
                            <td data-label="Title"><strong>Implement Dark Mode</strong></td>
                            <td data-label="Project">Skyblocker</td>
                            <td data-label="Status"><span class="badge blue">New</span></td>
                            <td data-label="Priority"><span class="badge green">Low</span></td>
                            <td data-label="Assigned">
                                <div class="avatar-line">
                                    <img src="../../assets/images/icon.png" title="Unassigned" alt="profile-picture" class="profile-pic-mini">
                                </div>
                            </td>
                            <td data-label="Actions"><a href="./ticket-details.php<?= $debug ?>" class="icon"><i class="fa-solid fa-arrow-up-right-from-square"></i></a></td>
                        </tr>
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