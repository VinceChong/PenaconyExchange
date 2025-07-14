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
            .game-card {
                display: inline-block;
                margin: 20px;
                text-align: center;
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
        
        <div class = "page-wrapper">
            <div class="page-content">
                <div id="gameList"></div>
            </div>
        </div>

        <?php include("../includes/footer.php"); ?>
        <script src = "/PenaconyExchange/scripts/home.js"></script>
    </body>
</html>