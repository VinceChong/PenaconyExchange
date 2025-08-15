<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION["user"])) {
    header("Location: /PenaconyExchange/pages/authentication.php");
    exit;
}

require_once("../db/backend/db.php"); // Your database connection file

$userId = $_SESSION["user"]["userId"]; // Assuming user ID is stored here

// Fetch games in the user's wish list
$query = "
    SELECT *
    FROM Wishlist w
    INNER JOIN Game g ON w.gameId = g.gameId
    WHERE w.userId = ?
";
$stmt = $connect->prepare($query);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

$totalValue = 0;
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Wishlist</title>
    <link rel="stylesheet" href="/PenaconyExchange/styles/common.css"/>
    <link rel="stylesheet" href="/PenaconyExchange/styles/wishlist.css"/>
    <link rel="icon" href="/PenaconyExchange/assets/image/harmony.png">
</head>

<body>
<?php include("../includes/header.php"); ?>

<div class="pageWrapper">
    <div class="pageContent">
        <h1>My Wishlist</h1>

        <?php if ($result->num_rows > 0): ?>
            <div class="wishListContainer">
                <?php while ($row = $result->fetch_assoc()): ?>
                    <div class="wishListItem">
                        <img src="<?php echo htmlspecialchars($row['mainPicture']); ?>" 
                             alt="<?php echo htmlspecialchars($row['gameTitle']); ?>">
                        <h2><?php echo htmlspecialchars($row['gameTitle']); ?></h2>
                        <p><?php echo nl2br(htmlspecialchars($row['gameDesc'])); ?></p>
                        
                        <!-- Add to Cart -->
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="game_id" value="<?php echo $row['gameId']; ?>">
                            <input type="hidden" name="action" value="add_to_cart">
                            <button type="submit" class="cartBtn">Add to Cart</button>
                        </form>

                        <!-- Remove from Wishlist -->
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="game_id" value="<?php echo $row['gameId']; ?>">
                            <input type="hidden" name="action" value="remove">
                            <button type="submit" class="removeBtn">Remove</button>
                        </form>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <p>Your wishlist is empty. Kindly add your favourites! 
               <a href="/PenaconyExchange/pages/publisherProfile.php">Browse games</a>!</p>
        <?php endif; ?>

        <hr>

        <!-- Example Add to Wishlist Form -->
        <h2>Search for a Game to Add to Wishlist</h2>
        <button onclick="window.location.href='/PenaconyExchange/index.php'" class="addBtn">
            Add More Now!
        </button>

    </div>
</div>

<?php include("../includes/footer.php"); ?>
<script src="/PenaconyExchange/scripts/wishlist.js"></script>
</body>
</html>
