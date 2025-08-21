<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION["user"])) {
    header("Location: /PenaconyExchange/pages/authentication.php");
    exit;
}

// Database connection
include("../db/backend/db.php"); // Database connection file

$user = $_SESSION["user"];
$userId = $user["userId"];
$username = $user["username"];
$profilePicture = $user["profilePicture"];

// Fetch all games in the user's wishlist
function retrieveWishlist($connect, $userId) {
    $query = "
        SELECT g.gameId, g.gameTitle, g.gameDesc, g.mainPicture
        FROM Wishlist w
        INNER JOIN Game g ON w.gameId = g.gameId
        WHERE w.userId = ?
    ";

    $stmt = $connect->prepare($query);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    return $stmt->get_result();
}

$result = retrieveWishlist($connect, $userId);

if ($result === false) {
    die("Query failed: " . $connect->error);
}
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
            <div class="profileContainer">
                <form action="../PenaconyExchange/db/backend/updateProfilePicture.php" method="POST" enctype="multipart/form-data">
                    <div class="profileCard">
                        <label for="profilePictureInput">
                            <img src="<?php echo $profilePicture; ?>" class="profilePicture" id="profilePicturePreview">
                        </label>
                        <input type="file" name="profilePicture" id="profilePictureInput" accept="image/*" style="display:none;" onchange="previewImage(event)">
                        <div class="username wishlist">
                            <h1><?php echo htmlspecialchars($username); ?>'s Wishlist</h1>
                        </div>
                    </div>
                </form>

                <div id="cartHeader" style="display:flex;">
                    <a href="/PenaconyExchange/pages/cart.php" target="_blank">
                        <button id="cart">Cart</button>
                    </a>
                </div>

                <?php if ($result->num_rows > 0): ?>
                    <div class="wishListContainer">
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <div class="wishListItem">
                                <img src="<?php echo htmlspecialchars($row['mainPicture']); ?>" 
                                     alt="<?php echo htmlspecialchars($row['gameTitle']); ?>">
                                <h2><?php echo htmlspecialchars($row['gameTitle']); ?></h2>
                                <p><?php echo nl2br(htmlspecialchars($row['gameDesc'])); ?></p>
                                
                                <!-- Add to Cart -->
                                <form method="POST" action="../db/backend/cartActions.php" style="display:inline;">
                                    <input type="hidden" name="game_id" value="<?php echo $row['gameId']; ?>">
                                    <input type="hidden" name="action" value="add_to_cart">
                                    <button type="submit" class="cartBtn">Add to Cart</button>
                                </form>

                                <!-- Remove from Wishlist -->
                                <form method="POST" action="../db/backend/wishlistActions.php" style="display:inline;">
                                    <input type="hidden" name="game_id" value="<?php echo $row['gameId']; ?>">
                                    <input type="hidden" name="action" value="remove">
                                    <button type="submit" class="removeBtn">Remove</button>
                                </form>
                            </div>
                        <?php endwhile; ?>
                    </div>
                <?php else: ?>
                    <p>Your wishlist is empty. Kindly add your favourites!</p>
                    <p><a href="/PenaconyExchange/pages/publisher.php" target="_blank">Browse games now</a>!</p>
                <?php endif; ?>

                <hr>

                <!-- Example Add to Wishlist Form -->
                <h2>Search for a Game to Add to Wishlist</h2>
                <a href="/PenaconyExchange/index.php" target="_blank">
                    <button id="addmore" style="color: black;">Add More Now!</button>
                </a>
            </div>
        </div>
    </div>

    <?php include("../includes/footer.php"); ?>
    <script src="/PenaconyExchange/scripts/wishlist.js"></script>
</body>
</html>
