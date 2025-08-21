<?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (!isset($_SESSION["user"])) {
        header("Location: /PenaconyExchange/pages/authentication.php");
        exit;
    }

    // Database connection
    $user = $_SESSION["user"];
    $username = $user["username"];
    $profilePicture = $user["profilePicture"];

    if($profilePicture === "N/A" || empty($profilePicture)){
        $profilePicture = "/PenaconyExchange/db/assets/profile/default.jpg";
    }

    include("../db/backend/db.php"); // adjust to DB connection file path

    $userId = $_SESSION["user"]["userId"]; // Assuming user ID is stored here

    function retrieveGameDetails($connect, $gameId) {
        $query = "
            SELECT *
            FROM Game g
            JOIN AboutGame ab ON g.gameId = ab.gameId
            JOIN Publisher p ON g.publisherId = p.publisherId
            WHERE g.gameId = ?
            ";

        $statement = $connect->prepare($query);
        $statement->bind_param("i", $gameId);
        $statement->execute();
        $result = $statement->get_result();
        return $result->fetch_assoc();

    }

        $gameId=1;
        $gameData= retrieveGameDetails($connect, $gameId);

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
                <h1>My Cart</h1>

                <div id="wishListHeader" style="display:flex;">
                    <a href="/PenaconyExchange/pages/wishlist.php" target="_blank">
                        <button id="wishlist">Wishlist</button>
                    </a>
                </div>
                
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
