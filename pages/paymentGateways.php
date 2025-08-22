<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION["user"])) {
    header("Location: /PenaconyExchange/pages/authentication.php");
    exit;
}

// Database connection
include("../db/backend/db.php");

$user = $_SESSION["user"];
$username = $user["username"];
$profilePicture = $user["profilePicture"];

if($profilePicture === "N/A" || empty($profilePicture)){
    $profilePicture = "/PenaconyExchange/db/assets/profile/default.jpg";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Payment Gateway</title>
    <link rel="stylesheet" href="/PenaconyExchange/styles/common.css"/>
    <link rel="stylesheet" href="/PenaconyExchange/styles/payment.css"/>
    <link rel="icon" href="/PenaconyExchange/assets/image/harmony.png">
    <style>
        .payment-container {
            max-width: 800px;
            margin: 20px auto;
            background-color: #1b2838;
            border-radius: 5px;
            padding: 20px;
        }
        
        .payment-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            padding-bottom: 15px;
            border-bottom: 1px solid #2a475e;
        }
        
        .payment-title {
            font-size: 24px;
            font-weight: bold;
            color: #ffffff;
        }
        
        .payment-steps {
            display: flex;
            margin-bottom: 30px;
            background-color: #2a475e;
            border-radius: 3px;
            padding: 10px;
        }
        
        .step {
            flex: 1;
            text-align: center;
            padding: 10px;
            font-weight: bold;
        }
        
        .step.active {
            color: #66c0f4;
            border-bottom: 2px solid #66c0f4;
        }
        
        .step.inactive {
            color: #8f98a0;
        }
        
        .payment-methods {
            margin-bottom: 30px;
        }
        
        .section-title {
            font-size: 18px;
            font-weight: bold;
            color: #ffffff;
            margin-bottom: 15px;
        }
        
        .payment-options {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-bottom: 20px;
        }
        
        .payment-option {
            background-color: #2a475e;
            border: 2px solid transparent;
            border-radius: 5px;
            padding: 15px;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .payment-option:hover {
            border-color: #66c0f4;
            background-color: #3a577e;
        }
        
        .payment-option.selected {
            border-color: #66c0f4;
            background-color: #3a577e;
        }
        
        .payment-option img {
            width: 60px;
            height: 40px;
            object-fit: contain;
            margin-bottom: 10px;
        }
        
        .payment-option-name {
            font-weight: bold;
            color: #ffffff;
            margin-bottom: 5px;
        }
        
        .payment-info {
            background-color: #2a475e;
            border-radius: 5px;
            padding: 15px;
            margin-bottom: 20px;
        }
        
        .info-text {
            color: #c7d5e0;
            margin-bottom: 10px;
            line-height: 1.5;
        }
        
        .warning-text {
            color: #ff6633;
            font-size: 14px;
            margin-bottom: 15px;
        }
        
        .region-notice {
            background-color: #4c6b22;
            color: #a4d007;
            padding: 10px;
            border-radius: 3px;
            margin-bottom: 20px;
            font-size: 14px;
        }
        
        .review-notice {
            background-color: #2a475e;
            color: #c7d5e0;
            padding: 15px;
            border-radius: 3px;
            margin-bottom: 20px;
            text-align: center;
        }
        
        .continue-btn {
            background-color: #5c7e10;
            color: white;
            border: none;
            padding: 15px 30px;
            border-radius: 3px;
            cursor: pointer;
            font-size: 16px;
            font-weight: bold;
            width: 100%;
            margin-top: 20px;
        }
        
        .continue-btn:hover {
            background-color: #4c6b0d;
        }
        
        .continue-btn:disabled {
            background-color: #4a6580;
            cursor: not-allowed;
        }
        
        .accepted-methods {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #2a475e;
        }
        
        .methods-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(100px, 1fr));
            gap: 15px;
            margin-top: 15px;
        }
        
        .method-item {
            background-color: #2a475e;
            border-radius: 5px;
            padding: 10px;
            text-align: center;
        }
        
        .method-item img {
            width: 50px;
            height: 30px;
            object-fit: contain;
            margin-bottom: 5px;
        }
        
        .method-name {
            font-size: 12px;
            color: #c7d5e0;
        }
    </style>
</head>
<body>
    <?php include("../includes/header.php"); ?>

    <div class="pageWrapper">
        <div class="pageContent">
            <div class="payment-container">
                <div class="payment-header">
                    <h1 class="payment-title">Payment Information</h1>
                </div>
                
                <div class="payment-steps">
                    <div class="step active">Payment Info</div>
                    <div class="step inactive">Review + Purchase</div>
                </div>
                
                <div class="payment-methods">
                    <h2 class="section-title">PAYMENT METHOD</h2>
                    <p class="info-text">Please select a payment method</p>
                    
                    <div class="payment-options">
                        <div class="payment-option" onclick="selectPaymentMethod('shopeepay')">
                            <div class="payment-option-name">ShopeePay</div>
                        </div>
                    </div>
                    
                    <div class="payment-info">
                        <p class="warning-text">
                            Purchases made with this payment method can only be refunded to your Steam Wallet.
                        </p>
                        <p class="info-text">
                            <a href="#" style="color: #66c0f4;">Learn More</a>
                        </p>
                    </div>
                    
                    <div class="region-notice">
                        If your billing address is not in Malaysia, please set your store region preference
                    </div>
                    
                    <div class="review-notice">
                        You'll have a chance to review your order before it's placed.
                    </div>
                </div>
                
                <button class="continue-btn" onclick="proceedToReview()">Continue</button>
                
                <div class="accepted-methods">
                    <h3 class="section-title">PAYMENT METHODS</h3>
                    <p class="info-text">We accept the following secure payment methods:</p>
                    
                    <div class="methods-grid">
                        <div class="method-item">
                            <div class="method-name">VISA</div>
                        </div>
                        <div class="method-item">
                            <div class="method-name">GrobPay</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include("../includes/footer.php"); ?>
    
    <script>
        let selectedMethod = null;
        
        function selectPaymentMethod(method) {
            selectedMethod = method;
            
            // Update UI to show selected method
            document.querySelectorAll('.payment-option').forEach(option => {
                option.classList.remove('selected');
            });
            
            event.currentTarget.classList.add('selected');
            
            // Enable continue button
            document.querySelector('.continue-btn').disabled = false;
        }
        
        function proceedToReview() {
            if (!selectedMethod) {
                alert('Please select a payment method');
                return;
            }
            
            // Redirect to review page or process payment
            window.location.href = '/PenaconyExchange/pages/review.php?method=' + selectedMethod;
        }
        
        // Initialize - disable continue button until method is selected
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelector('.continue-btn').disabled = true;
        });
    </script>
</body>
</html>