<?php
session_start();
session_destroy();
header("Location: /Web-app-ticketing-php/index.php?toast=logged_out");
exit;
