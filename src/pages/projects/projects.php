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
                        <option value="">All</option>
                        <option value="In Progress">In Progress</option>
                        <option value="On Hold">On Hold</option>
                        <option value="Completed">Completed</option>
                    </select>
                </div>
                <div class="filter">
                    <label for="search">Research</label>
                    <input type="text" id="search" name="search" placeholder="Research a project">
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
                <table>
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
                    <tr>
                        <td data-label="ID">#101</td>
                        <td data-label="Project name"><strong>Skyblocker</strong></td>
                        <td data-label="Client">
                            <div class="user-profile-inline">
                                <img src="/src/assets/images/icon.png" class="profile-pic" alt="profile-picture" style="width:40px; height:40px;">
                                <span style="margin-left: var(--spacing-sm)">VicIsACat</span>
                            </div>
                        </td>
                        <td data-label="Status">
                            <span class="badge green">In progress</span>
                        </td>
                        <td data-label="Progress">
                            <div class="progress-container">
                                <div class="progress-bar">
                                    <div class="progress-fill" style="width: 3%;"></div>
                                </div>
                                <div class="progress-percentage">3%</div>
                            </div>
                        </td>
                        <td data-label="Creation date">52/02/1996</td>
                        <td data-label="Actions"><a href="./project-details.php<?=  $debugHandler->getDebugParam() ?>" class="icon"><i class="fa-solid fa-arrow-up-right-from-square"></i></a></td>
                    </tr>
                    <tr>
                        <td data-label="ID">#102</td>
                        <td data-label="Project name"><strong>Customizable Player Model</strong></td>
                        <td data-label="Client">
                            <div class="user-profile-inline">
                                <img src="/src/assets/images/icon.png" class="profile-pic" alt="profile-picture" style="width:40px; height:40px;">
                                <span style="margin-left: var(--spacing-sm)">tom54541</span>
                            </div>
                        </td>
                        <td data-label="Status">
                            <span class="badge orange">On Hold</span>
                        </td>
                        <td data-label="Progress">
                            <div class="progress-container">
                                <div class="progress-bar">
                                    <div class="progress-fill" style="width: 75%;"></div>
                                </div>
                                <div class="progress-percentage">75%</div>
                            </div>
                        </td>
                        <td data-label="Creation date">30/01/2026</td>
                        <td data-label="Actions"><a href="./project-details.php<?=  $debugHandler->getDebugParam() ?>" class="icon"><i class="fa-solid fa-arrow-up-right-from-square"></i></a></td>
                    </tr>
                    <tr>
                        <td data-label="ID">#102</td>
                        <td data-label="Project name"><strong>Customizable Player Model</strong></td>
                        <td data-label="Client">
                            <div class="user-profile-inline">
                                <img src="/src/assets/images/icon.png" class="profile-pic" alt="profile-picture" style="width:40px; height:40px;">
                                <span style="margin-left: var(--spacing-sm)">tom54541</span>
                            </div>
                        </td>
                        <td data-label="Status">
                            <span class="badge orange">On Hold</span>
                        </td>
                        <td data-label="Progress">
                            <div class="progress-container">
                                <div class="progress-bar">
                                    <div class="progress-fill" style="width: 75%;"></div>
                                </div>
                                <div class="progress-percentage">75%</div>
                            </div>
                        </td>
                        <td data-label="Creation date">30/01/2026</td>
                        <td data-label="Actions"><a href="./project-details.php<?=  $debugHandler->getDebugParam() ?>" class="icon"><i class="fa-solid fa-arrow-up-right-from-square"></i></a></td>
                    </tr>
                    <tr>
                        <td data-label="ID">#102</td>
                        <td data-label="Project name"><strong>Customizable Player Model</strong></td>
                        <td data-label="Client">
                            <div class="user-profile-inline">
                                <img src="/src/assets/images/icon.png" class="profile-pic" alt="profile-picture" style="width:40px; height:40px;">
                                <span style="margin-left: var(--spacing-sm)">tom54541</span>
                            </div>
                        </td>
                        <td data-label="Status">
                            <span class="badge orange">On Hold</span>
                        </td>
                        <td data-label="Progress">
                            <div class="progress-container">
                                <div class="progress-bar">
                                    <div class="progress-fill" style="width: 75%;"></div>
                                </div>
                                <div class="progress-percentage">75%</div>
                            </div>
                        </td>
                        <td data-label="Creation date">30/01/2026</td>
                        <td data-label="Actions"><a href="project-details.php<?=  $debugHandler->getDebugParam() ?>" class="icon"><i class="fa-solid fa-arrow-up-right-from-square"></i></a></td>
                    </tr>
                    <tr>
                        <td data-label="ID">#102</td>
                        <td data-label="Project name"><strong>Customizable Player Model</strong></td>
                        <td data-label="Client">
                            <div class="user-profile-inline">
                                <img src="/src/assets/images/icon.png" class="profile-pic" alt="profile-picture" style="width:40px; height:40px;">
                                <span style="margin-left: var(--spacing-sm)">tom54541</span>
                            </div>
                        </td>
                        <td data-label="Status">
                            <span class="badge orange">On Hold</span>
                        </td>
                        <td data-label="Progress">
                            <div class="progress-container">
                                <div class="progress-bar">
                                    <div class="progress-fill" style="width: 75%;"></div>
                                </div>
                                <div class="progress-percentage">75%</div>
                            </div>
                        </td>
                        <td data-label="Creation date">30/01/2026</td>
                        <td data-label="Actions"><a href="project-details.php<?=  $debugHandler->getDebugParam() ?>" class="icon"><i class="fa-solid fa-arrow-up-right-from-square"></i></a></td>
                    </tr>
                    <tr>
                        <td data-label="ID">#102</td>
                        <td data-label="Project name"><strong>Customizable Player Model</strong></td>
                        <td data-label="Client">
                            <div class="user-profile-inline">
                                <img src="/src/assets/images/icon.png" class="profile-pic" alt="profile-picture" style="width:40px; height:40px;">
                                <span style="margin-left: var(--spacing-sm)">tom54541</span>
                            </div>
                        </td>
                        <td data-label="Status">
                            <span class="badge orange">On Hold</span>
                        </td>
                        <td data-label="Progress">
                            <div class="progress-container">
                                <div class="progress-bar">
                                    <div class="progress-fill" style="width: 75%;"></div>
                                </div>
                                <div class="progress-percentage">75%</div>
                            </div>
                        </td>
                        <td data-label="Creation date">30/01/2026</td>
                        <td data-label="Actions"><a href="project-details.php<?=  $debugHandler->getDebugParam() ?>" class="icon"><i class="fa-solid fa-arrow-up-right-from-square"></i></a></td>
                    </tr>
                    <tr>
                        <td data-label="ID">#102</td>
                        <td data-label="Project name"><strong>Customizable Player Model</strong></td>
                        <td data-label="Client">
                            <div class="user-profile-inline">
                                <img src="/src/assets/images/icon.png" class="profile-pic" alt="profile-picture" style="width:40px; height:40px;">
                                <span style="margin-left: var(--spacing-sm)">tom54541</span>
                            </div>
                        </td>
                        <td data-label="Status">
                            <span class="badge orange">On Hold</span>
                        </td>
                        <td data-label="Progress">
                            <div class="progress-container">
                                <div class="progress-bar">
                                    <div class="progress-fill" style="width: 75%;"></div>
                                </div>
                                <div class="progress-percentage">75%</div>
                            </div>
                        </td>
                        <td data-label="Creation date">30/01/2026</td>
                        <td data-label="Actions"><a href="project-details.php<?=  $debugHandler->getDebugParam() ?>" class="icon"><i class="fa-solid fa-arrow-up-right-from-square"></i></a></td>
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
    import * as LangHandler from "/src/assets/js/language-handler.js";
    import { TableManager } from "/src/assets/js/table-handler.js";

    // Initialize for projects table (using 5 rows per page)
    new TableManager('.project table', 5);
    console.log("The current language is", LangHandler.getLanguage());
</script>
</html>