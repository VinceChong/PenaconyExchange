<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION["publisher"])) {
    header("Location: /PenaconyExchange/pages/publisher/publisherLogin.php");
    exit;
}

include "../../db/backend/db.php";
include "../../db/backend/retrieveGameDetail.php";

$gameId = $_GET['gameId'] ?? 0;
$gameDetails = retrieveGameDetail($connect, $gameId);
$systemReqs = retrieveSystemRequirement($connect, $gameId);

if (!$gameDetails) {
    die("Game not found.");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title><?= htmlspecialchars($gameDetails['gameTitle']) ?> - Maintenance</title>
    <link rel="stylesheet" href="/PenaconyExchange/styles/common.css"/>
    <link rel="stylesheet" href="/PenaconyExchange/styles/gameDetails.css"/>
    <link rel="icon" href="/PenaconyExchange/assets/image/harmony.png">
    <style>
        .game-header {
            display: flex;
            gap: 20px;
            background: #1b2838;
            padding: 20px;
            color: white;
        }
        .game-header img {
            width: 300px;
            height: 400px;
            object-fit: cover;
            border-radius: 8px;
        }
        .game-info {
            flex: 1;
        }
        .game-info h1 {
            margin-bottom: 10px;
        }
        .game-description {
            margin-top: 15px;
            color: #c7d5e0;
        }
        .system-req-container {
            margin-top: 20px;
        }
        .system-req-columns {
            display: flex;
            gap: 40px;
        }
        .system-req {
            flex: 1;
            background-color: #1b1b1b;
            padding: 15px;
            border-radius: 8px;
            color: #ccc;
        }
        .system-req h4 {
            color: #fff;
            margin-bottom: 10px;
        }
        .edit-buttons {
            margin-top: 20px;
        }
        .edit-buttons button {
            background: #66c0f4;
            color: black;
            border: none;
            padding: 10px 15px;
            margin-right: 10px;
            cursor: pointer;
            border-radius: 4px;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <?php include("../../includes/publisherHeader.php"); ?>

    <div class="pageWrapper">
        <div class="pageContent">
            <div class="game-header">
                <img src="<?= htmlspecialchars($gameDetails['mainPicture']) ?>" alt="<?= htmlspecialchars($gameDetails['gameTitle']) ?>">
                <div class="game-info">
                    <h1><?= htmlspecialchars($gameDetails['gameTitle']) ?></h1>
                    <p><strong>Publisher:</strong> <?= htmlspecialchars($gameDetails['username']) ?></p>
                    <p><strong>Price:</strong> RM<?= number_format($gameDetails['price'], 2) ?></p>
                    <div class="game-description">
                        <?= nl2br(htmlspecialchars($gameDetails['gameDesc'])) ?>
                    </div>
                    <div class="edit-buttons">
                        <button onclick="window.location.href='editGame.php?gameId=<?= $gameId ?>'">Edit Game Info</button>
                        <button onclick="window.location.href='editImages.php?gameId=<?= $gameId ?>'">Edit Images</button>
                        <button onclick="window.location.href='editRequirements.php?gameId=<?= $gameId ?>'">Edit System Requirements</button>
                    </div>
                </div>
            </div>

            <div class="system-req-container">
                <h3>System Requirements</h3>
                <div class="system-req-columns">
                    <div class="system-req">
                        <h4>Minimum:</h4>
                        <?php if ($systemReqs['Minimum']): ?>
                            <p><strong>OS:</strong> <?= htmlspecialchars($systemReqs['Minimum']['os']) ?></p>
                            <p><strong>Processor:</strong> <?= htmlspecialchars($systemReqs['Minimum']['processor']) ?></p>
                            <p><strong>Memory:</strong> <?= htmlspecialchars($systemReqs['Minimum']['memory']) ?></p>
                            <p><strong>Graphics:</strong> <?= htmlspecialchars($systemReqs['Minimum']['graphic']) ?></p>
                            <p><strong>Direct X:</strong> <?= htmlspecialchars($systemReqs['Minimum']['directX']) ?></p>
                            <p><strong>Storage:</strong> <?= htmlspecialchars($systemReqs['Minimum']['storage']) ?></p>
                            <p><strong>Sound Card:</strong> <?= htmlspecialchars($systemReqs['Minimum']['soundCard']) ?></p>
                        <?php else: ?>
                            <p>No minimum requirements set.</p>
                        <?php endif; ?>
                    </div>

                    <div class="system-req">
                        <h4>Recommended:</h4>
                        <?php if ($systemReqs['Recommended']): ?>
                            <p><strong>OS:</strong> <?= htmlspecialchars($systemReqs['Recommended']['os']) ?></p>
                            <p><strong>Processor:</strong> <?= htmlspecialchars($systemReqs['Recommended']['processor']) ?></p>
                            <p><strong>Memory:</strong> <?= htmlspecialchars($systemReqs['Recommended']['memory']) ?></p>
                            <p><strong>Graphics:</strong> <?= htmlspecialchars($systemReqs['Recommended']['graphic']) ?></p>
                            <p><strong>Direct X:</strong> <?= htmlspecialchars($systemReqs['Minimum']['directX']) ?></p>
                            <p><strong>Storage:</strong> <?= htmlspecialchars($systemReqs['Recommended']['storage']) ?></p>
                            <p><strong>Sound Card:</strong> <?= htmlspecialchars($systemReqs['Minimum']['soundCard']) ?></p>
                        <?php else: ?>
                            <p>No recommended requirements set.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <?php include("../../includes/footer.php"); ?>
</body>
</html>
