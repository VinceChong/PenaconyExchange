<?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (!isset($_SESSION["user"])) {
        header("Location: /PenaconyExchange/pages/authentication.php");
        exit;
    }
?>


<!DOCTYPE html>

<html lang = "en">
    <head>
        <meta charset="UTF-8"/>
        <meta name = "viewport" content = "width=device-width, initial-scale=1.0"/>
        <title> Search Result </title>
        <link rel = "stylesheet" href = "/PenaconyExchange/styles/common.css"/>
        <link rel = "stylesheet" href = "/PenaconyExchange/styles/searchResult.css"/>
        <link rel = "icon" href = "/PenaconyExchange/assets/image/harmony.png">
    </head>

    <body>
        <?php include("../includes/header.php"); ?>

        <div class = "pageWrapper">
            <div class="pageContent">
                <!-- Write the main content here -->
            </div>
        </div>
        
        <?php include("../includes/footer.php"); ?>
        <script src = "/PenaconyExchange/scripts/searchResult.js"></script>
    </body>
</html>