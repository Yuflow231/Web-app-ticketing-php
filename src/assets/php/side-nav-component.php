<?php
    $link = basename($_SERVER['PHP_SELF']);
    $no = pathinfo($link, PATHINFO_FILENAME);
    $sub = substr($no,   0, 5);

    $debug = isset($_GET["debug"]) ? "?debug=1" : "";
    $debugEnabled = isset($_GET["debug"]) ? "true" : "false";

    if(!is_null($_GET["debug"])){
        echo("<p class=\"debug-element\"> {$no} </p>");
        echo("<p class=\"debug-element\" style=\"background-color: transparent; color: red;\"> {$sub} </p>");
    }

    function laink($refLInk):void{
        global $sub, $debug; //need to create ref to global
        $split = basename($refLInk);
        if(str_contains($split, $sub)){
            echo("<a href='{$refLInk}{$debug}' class=\"active\">");
        }
        else {
            echo("<a href='{$refLInk}{$debug}'>");
        }
    }
?>

<nav class="navigation">
    <header class="top-bar">
        <div class="menu-bar">
            <span class="hamburger"><i class="fa-solid fa-bars"></i></span>
            <span>| Ticketing App</span>
        </div>
        <div class="user-profile-header">
            <a href="/Web-app-ticketing-php/src/pages/profile.php<?= $debug ?>" class="user-profile-inline">
                <span class="username" data-type="first-name">User</span>
                <span class="username" data-type="last-name">Name</span>
                <!-- placeholder using my YouTube profile pic -->
                <img src="https://yt3.ggpht.com/pz97Hxe-gW4DR1-S4HmoZopwKXppAHPajMDtCaSSM-3HNV31wECJmegkZAohyEh7qAbCNQAHUg=s176-c-k-c0x00ffffff-no-rj" alt="User Profile" class="profile-pic" >
            </a>
        </div>
    </header>

    <!-- Side Navigation Bar -->
    <div class="side-nav">
        <div class="top-side">
            <?php  laink("/Web-app-ticketing-php/src/pages/dashBoard.php") ?>
                <span class="icon"><i class="fa-solid fa-chart-line"></i></span>
                <span class="text">Dashboard</span>
            </a>
            <?php  laink("/Web-app-ticketing-php/src/pages/projects/projects.php") ?>
                <span class="icon"><i class="fa-solid fa-diagram-project"></i></span>
                <span class="text">Projects</span>
            </a>
            <?php  laink("/Web-app-ticketing-php/src/pages/tickets/tickets.php") ?>
                <span class="icon"><i class="fa-solid fa-ticket"></i></span>
                <span class="text">Tickets</span>
            </a>
            <?php  laink("/Web-app-ticketing-php/src/pages/profile.php") ?>
                <span class="icon"><i class="fa-solid fa-user"></i></span>
                <span class="text">Profile</span>
            </a>
        </div>

        <a href="/Web-app-ticketing-php/index.php<?= $debug ?>">
            <span class="icon"><i class="fa-solid fa-right-from-bracket"></i></span>
            <span class="text">Logout</span>
        </a>
    </div>
</nav>