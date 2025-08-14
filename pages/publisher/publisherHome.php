<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION["publisher"])) {
    header("Location: /PenaconyExchange/pages/publisher/publisherLogin.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang = "en">
    <head>
        <meta charset = "UTF-8"/>
        <meta name = "viewport" content="width=device-width, initial-scale=1.0"/>
        <title>Publisher Home</title>
        <link rel = "stylesheet" href = "/PenaconyExchange/styles/common.css"/>
        <link rel = "stylesheet" href = "/PenaconyExchange/styles/publisherHome.css"/>
        <link rel = "icon" href = "/PenaconyExchange/assets/image/harmony.png">
    </head>

    <body>
        <?php include("../../includes/publisherHeader.php"); ?>

        <div class="gamesWrapper">
            <h2 style="color:white;">Your Games</h2>
            <div id="gamesContainer"></div>
            <p id="noGamesMessage" class="noGames" style="display:none;">You have not uploaded any games yet.</p>
        </div>
        
        <?php include("../../includes/footer.php"); ?>

        <script src = "/PenaconyExchange/scripts/publisher/publisherHome.js"></script>
    </body>
</html>
