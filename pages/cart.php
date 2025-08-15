<?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (!isset($_SESSION["user"])) {
        header("Location: /PenaconyExchange/pages/authentication.php");
        exit;
    }

    // Database connection
    include("../db/backend/db.php"); // adjust to your DB connection file path

    $userId = $_SESSION["user"]["userId"];

        $query = "
            SELECT *
            FROM Cart c
            INNER JOIN Game g ON c.gameId = g.gameId
            WHERE c.userId = ?
        ";
        
        $stmt = $connect->prepare($query);
        $stmt->bind_param("i", $gameID);
        $stmt->execute();
        $result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
        <title>Cart</title>
        <link rel="stylesheet" href="/PenaconyExchange/styles/common.css"/>
        <link rel="stylesheet" href="/PenaconyExchange/styles/cart.css"/>
        <link rel="icon" href="/PenaconyExchange/assets/image/harmony.png">
    </head>
    <body>

        <div class="pageWrapper">
            <div class="pageContent">
                <?php if ($gameData): ?>
                    <h2><?php echo htmlspecialchars($gameData['gameTitle']); ?></h2>
                    <img src="/PenaconyExchange/assets/game_images/<?php echo htmlspecialchars($gameData['mainPicture']); ?>" 
                         alt="<?php echo htmlspecialchars($gameData['gameTitle']); ?>" 
                         style="max-width:300px;">
                <?php else: ?>
                    <p>Game not found.</p>
                <?php endif; ?>
            </div>
        </div>

        <?php include("../includes/footer.php"); ?>
        <script src="/PenaconyExchange/scripts/cart.js"></script>
    </body>
</html>
