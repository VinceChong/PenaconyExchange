<?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (!isset($_SESSION["user"])) {
        header("Location: /PenaconyExchange/pages/authentication.php");
        exit;
    }

    require_once("../db/backend/db.php");

    $userId = $_SESSION["user"]["userId"];

    $query = "
        SELECT g.gameId, g.gameTitle, g.mainPicture
        FROM purchasedgame p
        INNER JOIN game g ON p.gameId = g.gameId
        WHERE p.userId = ?
    ";
    $stmt = $connect->prepare($query);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();

    $games = [];
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $games[] = $row;
        }
    }

    
?>

<!DOCTYPE html>

<html lang="en">
    <head>
        <meta charset="UTF-8"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
        <title>Library</title>
        <link rel="stylesheet" href="/PenaconyExchange/styles/common.css"/>
        <link rel="stylesheet" href="/PenaconyExchange/styles/library.css?v=3"/>
        <link rel="icon" href="/PenaconyExchange/assets/image/harmony.png">
    </head>
    <style>
        .pageWrapper {
            background: #0f4c81;
        }
        .pageContent {
            display: flex;
            gap: 20px;
        }

        .left-side {
            background: #1b2838;
            border-radius: 8px;
            flex: 1;
        }

        .right-side {
            flex: 4;
            width: 70%;
            border-radius: 8px;
        }

        .sidebar {
            padding: 20px;
            background: #1b2838;
            border-radius: 8px;
        }

        .sidebar_item {
            display: flex;               
            align-items: center;         
            padding: 8px;
            text-decoration: none;
            color: inherit;
        }

        .sidebar_item img.games_image {
            width: 40px;                 
            height: 40px;                
            object-fit: cover;                    
            margin-right: 10px;          
        }

        .sidebar_details h3 {
            margin: 0;
            font-size: 14px;
            font-weight: normal;             
        }

        .sidebar_item:hover {
            background: #222;
        }
        /* Recent Games Section */

        .Games_list {
            display: flex;
            gap: 20px;
        }

        .recentGames_item {
            width: 180px;
            text-align: center;
            background: #333;
            text-decoration: none;
            color: inherit;
        }

        .recentGames_item:hover {
            background: #222;         
        }

        .recentGames_image {
            width: 200px;
            border-radius: 8px;
        }

        /* All Games Section */
        .allGames_image {
            max-height: 200px;
            border-radius: 8px;
        }



        
    </style>

    <body>
        <?php include("../includes/header.php"); ?>

        <div class="pageWrapper">
            <div class="pageContent">
                <div class="left-side">
                    <!-- left sidebar -->
                     <div class="sidebar">
                         <h2>Home</h2>
                         <section class="allGames">
                             <h5>ALL(<?php echo count($games); ?>)</h5>
                             <?php foreach ($games as $game): 
                            $title = htmlspecialchars($game['gameTitle'], ENT_QUOTES, 'UTF-8');
                            $img   = htmlspecialchars($game['mainPicture'], ENT_QUOTES, 'UTF-8');
                            $href  = "/PenaconyExchange/pages/purchasedgame.php?gameId=" . urlencode($game['gameId']);
                        ?>
                            <div>
                                <a href="<?php echo $href; ?>" class="sidebar_item">
                                <img src="<?php echo $img; ?>" alt="<?php echo $title; ?>" class="games_image" loading="lazy" decoding="async">
                                <div class="sidebar_details">
                                    <h3><?php echo $title; ?></h3>
                                </div>
                            </a>

                        </div>
                    <?php endforeach; ?>
                         </section>
                     </div>
                </div>
                <div class="right-side">
                <!-- Recent Games Section -->
                <section class="recentGames">
                    <h2>Recent Games</h2>
                    <div class="Games_list">
                        <?php foreach ($games as $game):
                            $img   = htmlspecialchars($game['mainPicture'], ENT_QUOTES, 'UTF-8');
                            $href  = "/PenaconyExchange/pages/purchasedgame.php?gameId=" . urlencode($game['gameId']);
                        ?>
                            <div>
                                <a href="<?php echo $href; ?>" class="recentGames_item">
                                    <img src="<?php echo $img; ?>" alt="<?php echo $title; ?>" class="recentGames_image" loading="lazy" decoding="async">
                                </a>
                            </div>

                        <?php endforeach; ?>

                    </div>
                </section>

                <!-- All Games Section -->
                <section class="allGames">
                    <h2>All Games (<?= count($games); ?>)</h2>
                    <div class="Games_list">
                        <?php foreach ($games as $game): 
                            $img  = htmlspecialchars($game['mainPicture'], ENT_QUOTES, 'UTF-8');
                            $href = "/PenaconyExchange/pages/purchasedgame.php?gameId=" . urlencode($game['gameId']);
                        ?>
                            <a href="<?= $href; ?>" class="allGames_item">
                                <img src="<?= $img; ?>" alt="Game" class="allGames_image" loading="lazy" decoding="async">
                            </a>
                        <?php endforeach; ?>
                    </div>
                </section>
            </div>
        </div>

        <?php include("../includes/footer.php"); ?>
    </body>
</html>