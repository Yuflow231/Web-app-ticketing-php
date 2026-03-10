<?php
    session_start();
    // Import debug handler
    require_once("../../assets/php/debug-handler.php");
    require_once("../../assets/php/db-handler.php");
    require_once("../../assets/php/table-handler.php");

    // Guard — kick back to login if not authenticated
    if (!isset($_SESSION['user'])) {
        header("Location: ../../../index.php?toast=not_logged_in");
        exit;
    }

    $debugHandler = DebugHandler::getInstance();
    $user = $_SESSION['user']; // shorthand for use in the page

    // Handle delete
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_project_id'])) {
        $deleteId = (int) $_POST['delete_project_id'];
        if ($deleteId > 0) {
            try {
                $db = DBHandler::getInstance();
                $db->deleteProject($deleteId);
                header("Location: ./projects.php?toast=project_deleted" . $debugHandler->getDebugAppend());
                exit;
            } catch (Exception $e) {
                $fwd = $debugHandler->getDebugForwardParams(['DB Error' => $e->getMessage()]);
                header("Location: ./projects.php?toast=db_error" . $debugHandler->getDebugAppend() . $fwd);
                exit;
            }
        }
    }

    // Fetch projects from DB based on role
    try {
        $db       = DBHandler::getInstance();
        $projects = $db->getProjectsForUser($user['id'], $user['role']);
    } catch (Exception $e) {
        $projects = [];
        $debugHandler->addInfoRight("DB Error", $e->getMessage());
    }

    $debugHandler->addGetParams();
    $debugHandler->addSeparatorRight();
    $debugHandler->addInfoLeft("Projects found", count($projects));
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
                        <th>Owner</th>
                        <th>Status</th>
                        <th>Progress</th>
                        <th>Creation date</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    <!-- Projects will be loaded here -->
                    <!-- Project template -->
                    <?php foreach ($projects  as $project): ?>
                    <!-- <?php if (isFiltered($project, ['status'])): ?> -->
                        <tr>
                            <td data-label="ID">#<?= $project["id"] ?></td>
                            <td data-label="Project name"><strong><?= $project["name"] ?></strong></td>
                            <td data-label="Client">
                                <div class="user-profile-inline">
                                    <?php $owner = $db->getProjectOwner($project["id"]); ?>

                                    <img src="../../../src/assets/images/<?= $owner["profile_pic"] ?>" class="profile-pic" alt="profile-picture" style="width:40px; height:40px;">
                                    <span style="margin-left: var(--spacing-sm)"><?= $owner["first_name"]. ' ' .$owner["last_name"] ?></span>
                                </div>
                            </td>
                            <td data-label="Status">
                                <span class="badge <?php setBadgeColor($project["status"]) ?> "> <?= $project["status"] ?></span>
                            </td>
                            <td data-label="Progress">
                                <div class="progress-container">
                                    <div class="progress-bar">
                                        <div class="progress-fill" style="width: <?= htmlspecialchars($project["progress_percent"]) ?>%;"></div>
                                    </div>
                                    <div class="progress-percentage"><?= $project["progress_percent"] ?>%</div>
                                </div>
                            </td>
                            <td data-label="Creation date"><?= $project["creation_date"] ?></td>
                            <td data-label="Actions">
                                <div style="display: flex; justify-content: space-evenly">
                                    <a href="./project-details.php?id=<?= $project["id"] ?><?=  $debugHandler->getDebugAppend() ?>" class="icon"><i class="fa-solid fa-arrow-up-right-from-square"></i></a>
                                    <form method="POST" style="display:inline;" onsubmit="return confirm('Delete this project? All associated tickets will also be deleted. This cannot be undone.')">
                                        <input type="hidden" name="delete_project_id" value="<?= $project['id'] ?>">
                                        <button type="submit" class="icon" style="color: var(--danger-color); background: none; border: none; cursor: pointer;">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <!--<?php endif; ?> -->
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
    import Toast from '../../assets/js/toast.js';
    import { TableManager } from "../../assets/js/table-handler.js";

    // Initialize for projects table (using 5 rows per page)
    new TableManager('.project table', 5);
    console.log("The current language is", LangHandler.getLanguage());

    const toastMessages = {
        project_deleted: { text: "Project deleted successfully.", type: "neutral" },
        db_error:        { text: "A database error occurred.", type: "error" },
    };
    const params = new URLSearchParams(window.location.search);
    const toastKey = params.get('toast');
    if (toastKey && toastMessages[toastKey]) {
        Toast(toastMessages[toastKey].text, toastMessages[toastKey].type);
        const cleanUrl = new URL(window.location.href);
        cleanUrl.searchParams.delete('toast');
        window.history.replaceState({}, '', cleanUrl);
    }
</script>
</html>