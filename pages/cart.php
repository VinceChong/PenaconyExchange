<?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (!isset($_SESSION["user"])) {
        header("Location: /PenaconyExchange/pages/authentication.php");
        exit;
    }

    // Database connection
    include("../db/backend/db.php"); // adjust to DB connection file path

    $user = $_SESSION["user"];
    $username = $user["username"];
    $profilePicture = $user["profilePicture"];

    if($profilePicture === "N/A" || empty($profilePicture)){
        $profilePicture = "/PenaconyExchange/db/assets/profile/default.jpg";
    }

    $userId = $_SESSION["user"]["userId"]; // Assuming user ID is stored here


    function retrieveGameDetails($connect, $gameId) {

        $query = "
            SELECT g.gameId, g.gameTitle, g.gameDesc, g.mainPicture, g.price
            FROM Cart c
            INNER JOIN Game g ON c.gameId = g.gameId
            WHERE c.gameId = ?
        ";

        $statement = $connect->prepare($query);
        $statement->bind_param("i", $gameId);
        $statement->execute();
        $result = $statement->get_result();
        $gameData = $result->fetch_assoc();
        
        // Ensure price is set and is a valid number
        if ($gameData && (!isset($gameData['price']) || !is_numeric($gameData['price']))) {
            $gameData['price'] = 0.00;
        }
        
        return $gameData;
    }

    // Remove this test code as it's not needed
    // $userId=1;
    // $testGameId=1;
    // $testGameData= retrieveGameDetails($connect, $testGameId);

    function retrieveCartGames($connect, $userId){
        $query = "
            SELECT g.gameId, g.gameTitle, g.gameDesc, g.mainPicture, g.price
            FROM Cart c
            INNER JOIN Game g ON c.gameId = g.gameId
            WHERE c.userId = ?
            GROUP BY g.gameId";

        $statement = $connect->prepare($query);
        $statement->bind_param("i", $userId);
        $statement->execute();
        $result = $statement->get_result();
        
        $games = [];
        while ($row = $result->fetch_assoc()) {
            // Ensure price is properly formatted as a float
            $row['price'] = isset($row['price']) && is_numeric($row['price']) ? (float)$row['price'] : 0.00;
            $games[] = $row;
        }
        return $games;
    }

    // Handle game removing action - fixed parameter name
    if(isset($_POST['remove_game']) && isset($_POST['game_id'])){
        $gameId = $_POST['game_id'];
        $removeQuery = "DELETE FROM Cart WHERE userId = ? AND gameId = ?";
        $stmt = $connect->prepare($removeQuery);
        $stmt->bind_param("ii", $userId, $gameId);
        $stmt->execute();
        
        // Refresh the page to show updated cart
        header("Location: ".$_SERVER['PHP_SELF']);
        exit();
    }

    $cartGames = retrieveCartGames($connect, $userId);

?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
        <title>Cart</title>
        <link rel="stylesheet" href="/PenaconyExchange/styles/common.css"/>
        <link rel = "stylesheet" href = "/PenaconyExchange/styles/cart.css"/>
        <link rel="icon" href="/PenaconyExchange/assets/image/harmony.png"/>
        <style>
            .cart-container {
                margin: 20px 0;
                border: 1px solid #ddd;
                border-radius: 5px;
                padding: 15px;
                background-color: #1b2838;
            }
            
            .cart-item {
                display: flex;
                align-items: center;
                padding: 15px;
                border-bottom: 1px solid #2a475e;
            }

            .cart-item:last-child {
                border-bottom: none;
            }

            .cart-item-image {
                width: 120px;
                height: 90px;
                object-fit: cover;
                margin-right: 15px;
            }

            .cart-item-details {
                flex-grow: 1;
            }

            .cart-item-title {
                font-weight: bold;
                color: #ffffff;
                margin-bottom: 5px;
            }

            .cart-item-price {
                color: #66c0f4;
                font-size: 16px;
            }

            .cart-item-actions {
                display: flex;
                flex-direction: column;
                align-items: flex-end;
            }

            .remove-btn {
                background-color: #ff4c4c;
                color: white;
                border: none;
                padding: 8px 12px;
                border-radius: 3px;
                cursor: pointer;
                margin-top: 10px;
            }

            .remove-btn:hover {
                background-color: #ff3333;
            }

            .estimated-total {
                margin-top: 20px;
                padding: 15px;
                background-color: #2a475e;
                border-radius: 5px;
            }

            .total-amount {
                font-size: 20px;
                font-weight: bold;
                color: #66c0f4;
            }

            .checkout-btn {
                background-color: #5c7e10;
                color: white;
                border: none;
                padding: 12px 20px;
                border-radius: 3px;
                cursor: pointer;
                margin-top: 15px;
                font-size: 16px;
            }

            .checkout-btn:hover {
                background-color: #4c6b0d;
            }

            .game-checkbox {
                margin-right: 15px;
                width: 20px;
                height: 20px;
            }

            .empty-cart {
                text-align: center;
                padding: 40px;
                color: #acb2b8;
            }
        </style>
    </head>

    <body>
        <?php include("../includes/header.php"); ?>

        <div class="pageWrapper">
            <div class="pageContent">
                <h1>My Cart</h1>

                <div id="wishListHeader" style="display:flex;">
                    <a href="/PenaconyExchange/pages/wishlist.php" target="_blank">
                        <button id="wishlist">Wishlist</button>
                    </a>
                </div>
                
                <div class="cart-container">
                    <?php if (count($cartGames) > 0): ?>
                        <form id="cart-form" method="POST">
                            <?php foreach ($cartGames as $game): ?>
                                <div class="cart-item">
                                    <input type="checkbox" class="game-checkbox" name="selected_games[]" value="<?php echo $game['gameId']; ?>" checked>
                                    <img class="cart-item-image" src="<?php echo htmlspecialchars($game['mainPicture']); ?>" alt="<?php echo htmlspecialchars($game['gameTitle']); ?> thumbnail">
                                    <div class="cart-item-details">
                                        <div class="cart-item-title"><?php echo htmlspecialchars($game['gameTitle']); ?></div>
                                        <div class="cart-item-price">RM<?php 
                                            // Safely format the price with proper error handling
                                            if (isset($game['price']) && is_numeric($game['price'])) {
                                                echo number_format((float)$game['price'], 2);
                                            } else {
                                                echo "0.00";
                                            }
                                        ?></div>
                                    </div>
                                    <div class="cart-item-actions">
                                        <form method="POST" style="display:inline;">
                                            <input type="hidden" name="game_id" value="<?php echo $game['gameId']; ?>">
                                            <button type="submit" name="remove_game" class="remove-btn">Remove</button>
                                        </form>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </form>
                    <?php else: ?>
                        <div class="empty-cart">
                            <h2>Your cart is empty</h2>
                            <p>Add some games to your cart to see them here</p>
                        </div>
                    <?php endif; ?>
                </div>
                
                <?php if (count($cartGames) > 0): ?>
                <div class="estimated-total">
                    <h3>Estimated Total</h3>
                    <div class="total-amount" id="total-amount">RM0.00</div>
                    <p>Sales tax will be calculated during checkout where applicable</p>
                    <button class="checkout-btn">
                        <a href="/PenaconyExchange/pages/paymentGateways.php" target="_blank">
                    Continue to payment</button>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <?php include("../includes/footer.php"); ?>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Calculate initial total
                calculateTotal();
                
                // Add event listeners to checkboxes
                const checkboxes = document.querySelectorAll('.game-checkbox');
                checkboxes.forEach(checkbox => {
                    checkbox.addEventListener('change', calculateTotal);
                });
                
                function calculateTotal() {
                    let total = 0;
                    
                    // Get all checked game items
                    const checkedItems = document.querySelectorAll('.game-checkbox:checked');
                    
                    checkedItems.forEach(checkbox => {
                        const cartItem = checkbox.closest('.cart-item');
                        const priceElement = cartItem.querySelector('.cart-item-price');
                        if (priceElement) {
                            const priceText = priceElement.textContent;
                            // Extract numeric value from price string (e.g., "RM36.60")
                            const price = parseFloat(priceText.replace('RM', '').replace(',', ''));
                            if (!isNaN(price)) {
                                total += price;
                            }
                        }
                    });
                    
                    // Update total display
                    document.getElementById('total-amount').textContent = 'RM' + total.toFixed(2);
                }
            });
        </script>
    </body>
</html>