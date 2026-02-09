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
            <h1>Ticket #101: Customizable UI bars</h1>
        </header>

        <div class="ticket-lifecycle-container">
            <div class="lifecycle-step achieved">New</div>
            <div class="lifecycle-step achieved">To be Approved</div>
            <div class="lifecycle-step current">In Progress</div>
            <div class="lifecycle-step">Completed</div>
        </div>

        <div class="detail-container">
            <section class="detail-card">
                <div class="detail-item">
                    <label>Title</label>
                    <h2>Customizable UI bars</h2>
                </div>

                <div class="detail-item" >
                    <label>Detailed Description</label>
                    <p>Create modulable and customizable bars to replace the default bars of Hypixel Skyblock</p>
                </div>

                <div class="inline-elements">
                    <div class="detail-item">
                        <label>Actual Time Spent</label>
                        <p>4.5 Hours</p>
                    </div>
                    <div class="detail-item">
                        <label>Estimated Time</label>
                        <p>8.0 Hours</p>
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
                        <span class="badge green">In Progress</span>
                    </div>
                    <div class="detail-item">
                        <label>Priority</label>
                        <span class="badge orange">Medium</span>
                    </div>
                    <div class="detail-item">
                        <label>Type</label>
                        <span>Included</span>
                    </div>
                </section>

                <section class="detail-card">
                    <h2>Assigned Collaborators</h2>
                    <div id="collaborator-list">
                        <div class="user-profile-inline" style="margin-bottom: var(--spacing-sm);">
                            <img src="/src/assets/images/icon.png" alt="User Profile" class="profile-pic" >
                            <div class="item-stacked" style="margin-left: var(--spacing-sm);">
                                <div>
                                    <span class="username" data-type="first-name">VicIsACat</span>
                                    <span class="username" data-type="last-name"></span>
                                </div>
                                <span class="user-role">Cat</span>
                            </div>
                        </div>
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