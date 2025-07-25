<!DOCTYPE html>

<html lang = "en">
    <head>
        <meta charset="UTF-8"/>
        <meta name = "viewport" content = "width=device-width, initial-scale=1.0"/>
        <title> Home </title>
        <link rel = "stylesheet" href = "/PenaconyExchange/styles/common.css"/>
        <link rel = "stylesheet" href = "/PenaconyExchange/styles/home.css"/>
        <link rel = "icon" href = "/PenaconyExchange/assets/image/harmony.png">

        <style>

            .page-content {
                background: #0f4c81;
            }
            .game-card {
                display: inline-block;
                margin: 20px;
                text-align: center;
                color: #ffffff;
                font-family: Roboto;
            }

            .game-card img {
                width: 200px;
                border-radius: 10px;
                cursor: pointer;
            }
        </style>
    </head>

    <body>
        <?php include("../includes/header.php"); ?>

        <div class = "pageWrapper">
            <div class="pageContent">
                <?php include("../includes/subHeader.php"); ?>
                <div id="gameList"></div>
            </div>
        </div>

        <?php include("../includes/footer.php"); ?>
        <script src = "/PenaconyExchange/scripts/home.js"></script>
    </body>
</html>