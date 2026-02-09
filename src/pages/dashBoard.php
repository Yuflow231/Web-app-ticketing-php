<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Ticketing App</title>
    <link rel="stylesheet" href="../assets/css/main.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/js/all.min.js"></script>
    <script type="module" src="../assets/js/side-bar.js"></script>

</head>
<body>
    <!-- Navigation Bar -->
    <?php require_once("../assets/php/side-nav-component.php"); ?>

    <!-- Main Content -->
    <main class="main-content">
        <header class="page-header">
            <h1>Dashboard</h1>
        </header>

        <!-- Statistics Section -->
        <section id="statistics">
            <h2>Statistics</h2>
            <div class="grid-stats">
                <div class="stat-card">
                    <div class="icon"><i class="fa-solid fa-diagram-project"></i></div>
                    <div class="stat-details">
                        <h3 id="stat-projects">0</h3>
                        <p class="text">Active projects</p>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="icon"><i class="fa-solid fa-ticket"></i></div>
                    <div class="stat-details">
                        <h3 id="stat-tickets">0</h3>
                        <p class="text">Active Tickets</p>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="icon"><i class="fa-solid fa-triangle-exclamation"></i></div>
                    <div class="stat-details">
                        <h3 id="stat-urgent">0</h3>
                        <p class="text">Urgent Tickets</p>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="icon"><i class="fa-solid fa-circle-check"></i></div>
                    <div class="stat-details">
                        <h3 id="stat-closed">0</h3>
                        <p class="text">Closed Tickets</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Recent Projects Section -->
        <section id="projects">
            <h2>Recent projects</h2>
            <div class="grid-content" id="recent-projects">
                <!-- Projects will be loaded here -->
                <div class="card flex-column">
                    <h3>Skyblocker</h3>
                    <p>Tickets: 2</p>
                    <p>Status:  <span class="badge green">In Progress</span></p>
                </div>
                <div class="card flex-column">
                    <h3>Project B</h3>
                    <p>Tickets: 8</p>
                    <p>Status:  <span class="badge orange">On Hold</span></p>
                </div>
                <div class="card flex-column">
                    <h3>Project C</h3>
                    <p>Tickets: 1</p>
                    <p>Status:  <span class="badge red">Closed</span></p>
                </div>
            </div>
        </section>

        <!-- Recent Tickets Section -->
        <section class="tickets">
            <h2>Recent tickets</h2>
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
                    <tbody id="recent-tickets">
                    <!-- Tickets will be loaded here -->
                    <tr>
                        <td data-label="ID">#101</td>
                        <td data-label="Title"><strong>Customizable UI bars</strong></td>
                        <td data-label="Project">Skyblocker</td>
                        <td data-label="Status"><span class="badge green">In Progress</span></td>
                        <td data-label="Priority"><span class="badge orange">Medium</span></td>
                        <td data-label="Assigned">
                            <div class="avatar-line">
                                <img src="../assets/images/icon.png" title="Unassigned" alt="profile-picture" class="profile-pic-mini">
                            </div>
                        </td>
                        <td data-label="Actions"><a href="./tickets/ticket-details.php<?= $debug ?>" class="icon"><i class="fa-solid fa-arrow-up-right-from-square"></i></a></td>
                    </tr>
                    <tr>
                        <td data-label="ID">#105</td>
                        <td data-label="Title"><strong>Implement Dark Mode</strong></td>
                        <td data-label="Project">Skyblocker</td>
                        <td data-label="Status"><span class="badge blue">New</span></td>
                        <td data-label="Priority"><span class="badge green">Low</span></td>
                        <td data-label="Assigned">
                            <div class="avatar-line">
                                <img src="../assets/images/icon.png" title="Unassigned" alt="profile-picture" class="profile-pic-mini">
                                <img src="../assets/images/icon.png" title="Unassigned" alt="profile-picture" class="profile-pic-mini">
                            </div>
                        </td>
                        <td data-label="Actions"><a href="./tickets/ticket-details.php<?= $debug ?>" class="icon"><i class="fa-solid fa-arrow-up-right-from-square"></i></a></td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </section>
    </main>
</body>
</html>