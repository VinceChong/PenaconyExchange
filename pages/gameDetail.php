<?php
    include "../db/backend/db.php";
    include "../db/backend/retrieveGameDetail.php";

    $gameId = $_GET['gameId'] ?? 0;
    $gameDetails = getGameDetail($connect, $gameId);
?>

<!DOCTYPE html>

<html lang = "en">
    <head>
        <meta charset="UTF-8"/>
        <meta name = "viewport" content = "width=device-width, initial-scale=1.0"/>
        <title> Game Detail </title>
        <link rel = "stylesheet" href = "/PenaconyExchange/styles/common.css"/>
        <link rel = "stylesheet" href = "/PenaconyExchange/styles/gameDetail.css"/>
        <link rel = "icon" href = "/PenaconyExchange/assets/image/harmony.png">

        <style>
            .banner {
                width: 100%;
                height: 300px;
                background-image: url('<?= $gameDetails['imageUrl'] ?>');
                background-size: cover;
                background-position: center;
                border-radius: 10px;
                margin-bottom: 20px;
            }
        </style>
    </head>

    <body>
        <?php include("../includes/header.php"); ?>

        <div class = "banner">
            <div class = "main-info">
                <img src = "<?= $gameDetails['mainPicture'] ?>" alt = "<?= $gameDetails['gameTitle'] ?>">
                
                <div class = "details">
                    <h1> <?= $gameDetails['gameTitle'] ?> </h1>
                    <p><strong>Release Date:</strong> <?= $gameDetails['releaseDate'] ?></p>
                    <p><strong>Categories:</strong> <?= $gameDetails['categories'] ?></p>
                    <div class="price">RM<?= number_format($gameDetails['price'], 2) ?></div>
                </div>
            </div>

            <div class="desc">
                <h2>About This Game</h2>
                <p><?= $gameDetails['detailedDesc'] ?></p>
                <iframe width="560" height="315" src="<?= $gameDetails['videoUrl'] ?>" frameborder="0" allowfullscreen></iframe>
            </div>
        </div>

        <?php include("../includes/footer.php"); ?>
        <script src = "/PenaconyExchange/scripts/gameDetail.js"></script>
    </body>
</html>