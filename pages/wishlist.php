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
        SELECT g.gameId, g.gameTitle, g.gameDesc, g.mainPicture, g.price,
               g.releaseDate
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

// Get wishlist items
$wishlistItems = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $wishlistItems[] = $row;
    }
}

// Handle sorting
$sortBy = isset($_GET['sort']) ? $_GET['sort'] : 'title';
if ($sortBy === 'date') {
    usort($wishlistItems, function($a, $b) {
        return strtotime($a['releaseDate']) - strtotime($b['releaseDate']);
    });
} else {
    // Default sort by title (alphabetical)
    usort($wishlistItems, function($a, $b) {
        return strcmp($a['gameTitle'], $b['gameTitle']);
    });
}

// Handle add to cart action
if (isset($_POST['add_to_cart']) && isset($_POST['selected_games'])) {
    $selectedGames = $_POST['selected_games'];
    
    foreach ($selectedGames as $gameId) {
        // Add game to cart
        $addToCartQuery = "INSERT INTO Cart (userId, gameId) VALUES (?, ?) 
                          ON DUPLICATE KEY UPDATE gameId = gameId";
        $stmt = $connect->prepare($addToCartQuery);
        $stmt->bind_param("ii", $userId, $gameId);
        $stmt->execute();
        
        // Remove game from wishlist
        $removeFromWishlistQuery = "DELETE FROM Wishlist WHERE userId = ? AND gameId = ?";
        $stmt = $connect->prepare($removeFromWishlistQuery);
        $stmt->bind_param("ii", $userId, $gameId);
        $stmt->execute();
    }
    
    // Refresh the page to show updated wishlist
    header("Location: " . $_SERVER['PHP_SELF'] . "?sort=" . $sortBy);
    exit();
}

// Handle individual remove action
if (isset($_POST['remove_from_wishlist']) && isset($_POST['game_id'])) {
    $gameId = $_POST['game_id'];
    
    $removeQuery = "DELETE FROM Wishlist WHERE userId = ? AND gameId = ?";
    $stmt = $connect->prepare($removeQuery);
    $stmt->bind_param("ii", $userId, $gameId);
    $stmt->execute();
    
    // Refresh the page
    header("Location: " . $_SERVER['PHP_SELF'] . "?sort=" . $sortBy);
    exit();
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
    <link rel="icon" href="/PenaconyExchange/assets/image/harmony.png"/>
    <?php echo realpath("../css/style.css"); ?>

    <style>
        .wishlist-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 1px solid #2a475e;
        }
        
        .wishlist-title {
            font-size: 24px;
            font-weight: bold;
            color: #ffffff;
        }
        
        .search-container {
            display: flex;
            gap: 15px;
            align-items: center;
        }
        
        .search-bar {
            padding: 10px 15px;
            border: 1px solid #4a6580;
            border-radius: 3px;
            background-color: #16202d;
            color: #c7d5e0;
            width: 300px;
        }
        
        .search-bar::placeholder {
            color: #6b8bad;
        }
        
        .sort-options {
            display: flex;
            align-items: center;
            gap: 10px;
            color: #c7d5e0;
        }
        
        .sort-select {
            padding: 8px 12px;
            border: 1px solid #4a6580;
            border-radius: 3px;
            background-color: #16202d;
            color: #c7d5e0;
            cursor: pointer;
        }
        
        .wishlist-item {
            background-color: #1b2838;
            border-radius: 5px;
            padding: 20px;
            margin-bottom: 20px;
            display: flex;
            gap: 20px;
            align-items: flex-start;
        }
        
        .game-checkbox {
            margin-right: 15px;
            width: 20px;
            height: 20px;
        }
        
        .game-image {
            width: 200px;
            height: 150px;
            object-fit: cover;
            border-radius: 3px;
            background-color: #2a475e;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #66c0f4;
        }
        
        .game-details {
            flex: 1;
        }
        
        .game-title {
            font-size: 20px;
            font-weight: bold;
            color: #ffffff;
            margin-bottom: 10px;
        }
        
        .game-info {
            display: flex;
            gap: 20px;
            margin-bottom: 15px;
            color: #c7d5e0;
        }
        
        .info-item {
            display: flex;
            flex-direction: column;
            gap: 5px;
        }
        
        .info-label {
            font-size: 12px;
            color: #8f98a0;
        }
        
        .info-value {
            font-weight: 500;
        }
        
        .price-section {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 15px;
        }
        
        .game-price {
            font-size: 18px;
            font-weight: bold;
            color: #66c0f4;
        }
        
        .wishlist-actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 15px;
        }
        
        .added-date {
            color: #8f98a0;
            font-size: 12px;
        }
        
        .remove-btn {
            background-color: transparent;
            color: #66c0f4;
            border: 1px solid #66c0f4;
            padding: 6px 12px;
            border-radius: 3px;
            cursor: pointer;
            font-size: 12px;
        }
        
        .remove-btn:hover {
            background-color: rgba(102, 192, 244, 0.1);
        }
        
        .empty-wishlist {
            text-align: center;
            padding: 60px 20px;
            color: #8f98a0;
        }
        
        .empty-wishlist h2 {
            font-size: 24px;
            margin-bottom: 10px;
            color: #c7d5e0;
        }
        
        .browse-btn {
            background-color: #5c7e10;
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 3px;
            cursor: pointer;
            margin-top: 20px;
            font-size: 14px;
        }
        
        .browse-btn:hover {
            background-color: #4c6b0d;
        }
        
        .cart-header {
            margin-bottom: 20px;
        }
        
        #cart {
            background-color: #5c7e10;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 3px;
            cursor: pointer;
            font-size: 14px;
        }
        
        #cart:hover {
            background-color: #4c6b0d;
        }
        
        .add-to-cart-btn {
            background-color: #5c7e10;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 3px;
            cursor: pointer;
            margin-top: 20px;
            font-size: 14px;
        }
        
        .add-to-cart-btn:hover {
            background-color: #4c6b0d;
        }
        
        .selection-actions {
            margin: 20px 0;
            text-align: right;
        }
        
        .select-all-container {
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px;
            border-radius: 3px;
            width: fit-content;
        }

        .select-all-container label {
            color: #ffffff;
            font-size: 14px;
        }

        .wishlist-item-image {
            width: 200px;
            border-radius: 10px;
        }

    </style>
</head>

<body>
    <?php include("../includes/header.php"); ?>

    <div class="pageWrapper">
        <div class="pageContent">
            <div class="profileContainer">
                <div class="profileCard">
                    <div class="username wishlist">
                        <h1><?php echo htmlspecialchars($username); ?>'S WISHLIST</h1>
                    </div>
                </div>

                <div class="cart-header">
                    <a href="/PenaconyExchange/pages/cart.php">
                        <button id="cart">Cart</button>
                    </a>
                </div>

                <div class="wishlist-header">
                    <div class="search-container">
                        <input type="text" class="search-bar" placeholder="Search by name" id="searchInput">
                    </div>
                    
                    <div class="sort-options">
                        <span>Sort by:</span>
                        <select class="sort-select" id="sortSelect" onchange="window.location.href='?sort='+this.value">
                            <option value="title" <?php echo $sortBy === 'title' ? 'selected' : ''; ?>>Alphabetical</option>
                            <option value="date" <?php echo $sortBy === 'date' ? 'selected' : ''; ?>>Released Date</option>
                        </select>
                    </div>
                </div>

                <?php if (count($wishlistItems) > 0): ?>
                <!-- ONE form for Add to Cart -->
                <form method="POST" action="/PenaconyExchange/pages/cart.php">
                    <div class="select-all-container">
                        <input type="checkbox" id="selectAll">
                        <label for="selectAll">Select All</label>
                    </div>

                    <div class="wishlist-container">
                        <?php foreach ($wishlistItems as $game): ?>
                            <div class="wishlist-item">
                                <input type="checkbox" class="game-checkbox" name="selected_games[]" value="<?php echo $game['gameId']; ?>">
                                
                                <div class="game-image">
                                    <img class="wishlist-item-image" 
                                        src="<?php echo htmlspecialchars($game['mainPicture']); ?>" 
                                        alt="<?php echo htmlspecialchars($game['gameTitle']); ?> thumbnail">
                                </div>
                                
                                <div class="game-details">
                                    <h2 class="game-title"><?php echo htmlspecialchars($game['gameTitle']); ?></h2>

                                    <div class="game-info">
                                        <div class="info-item">
                                            <span class="info-label">RELEASE DATE:</span>
                                            <span class="info-value"><?php echo isset($game['releaseDate']) ? htmlspecialchars($game['releaseDate']) : 'N/A'; ?></span>
                                        </div>
                                    </div>

                                    <div class="price-section">
                                        <span class="game-price">RM<?php 
                                            echo isset($game['price']) ? number_format((float)$game['price'], 2) : '0.00';
                                        ?></span>
                                    </div>

                                    <div class="wishlist-actions">
                                        <span class="added-date">Added on <?php echo date('d/m/Y'); ?></span>

                                        <!-- SEPARATE FORM for remove -->
                                        <form method="POST" action="/PenaconyExchange/pages/wishlist.php" style="display:inline;">
                                            <input type="hidden" name="game_id" value="<?php echo $game['gameId']; ?>">
                                            <input type="hidden" name="remove_from_wishlist" value="1">
                                            <button type="submit" class="remove-btn">remove</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <div class="selection-actions">
                        <button type="submit" name="add_to_cart" class="add-to-cart-btn">Add Selected to Cart</button>
                    </div>
                </form>

            <?php else: ?>
                <div class="empty-wishlist">
                    <h2>Your wishlist is empty</h2>
                    <p>Kindly add your favourites!</p>
                    <a href="/PenaconyExchange/pages/home.php">
                        <button class="browse-btn">Browse games now</button>
                    </a>
                </div>
            <?php endif; ?>

                </div>
            </div>
    </div>

    <?php include("../includes/footer.php"); ?>
    <script>

        // Search functionality
    document.getElementById('searchInput').addEventListener('input', function(e) {
        const searchTerm = e.target.value.toLowerCase();
        const items = document.querySelectorAll('.wishlist-item');
    
    items.forEach(item => {
        const title = item.querySelector('.game-title').textContent.toLowerCase();
        
        if (title.includes(searchTerm)) {
            item.style.display = 'flex';
        } else {
            item.style.display = 'none';
        }
    });
});

    // Select all functionality
    document.getElementById('selectAll').addEventListener('change', function(e) {
        const checkboxes = document.querySelectorAll('.game-checkbox');
        checkboxes.forEach(checkbox => {
            checkbox.checked = e.target.checked;
        });
    });

    // Update select all checkbox when individual checkboxes change
    const checkboxes = document.querySelectorAll('.game-checkbox');
    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const allChecked = document.querySelectorAll('.game-checkbox:checked').length === checkboxes.length;
            document.getElementById('selectAll').checked = allChecked;
        });
    });
    </script>
    </body>
</html>