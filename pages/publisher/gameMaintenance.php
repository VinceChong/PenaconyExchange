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
    $gameDetails = retrieveGameDetails($connect, $gameId);
    $systemReqs = retrieveSystemRequirements($connect, $gameId);
    $gamePreviews = retrieveGamePreviews($connect, $gameId);
    $gameCategories = retrieveGameCategories($connect, $gameId);

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
        <link rel="icon" href="/PenaconyExchange/assets/image/harmony.png">
        <style>
            body {
                background-color: #1b2838;
                color: #c7d5e0;
                font-family: Arial, sans-serif;
            }
            .pageWrapper {
                max-width: 1200px;
                margin: 0 auto;
                padding: 50px;
                padding-top: 80px; /* header height + spacing */
            }
            .game-main {
                display: flex;
                gap: 20px;
            }
            .left-side {
                flex: 2;
            }
            .video-player {
                background: black;
                border-radius: 6px;
                overflow: hidden;
                margin-bottom: 10px;
            }
            .video-player iframe, 
            .video-player video {
                width: 100%;
                height: 400px;
            }
            .preview-thumbs {
                display: flex;
                gap: 10px;
                flex-wrap: wrap;
            }
            .preview-thumbs img {
                width: 120px;
                height: 70px;
                object-fit: cover;
                border-radius: 4px;
                cursor: pointer;
                border: 2px solid transparent;
            }
            .preview-thumbs img:hover {
                border: 2px solid #66c0f4;
            }
            .right-side {
                flex: 1;
                background-color: #2a475e;
                border-radius: 6px;
                padding: 15px;
            }
            .right-side h1 {
                margin-top: 0;
                color: white;
            }
            .price {
                color: #66c0f4;
                font-size: 1.4em;
                margin: 10px 0;
            }
            .categories {
                margin-top: 10px;
            }
            .categories span {
                background-color: #1b2838;
                padding: 4px 8px;
                border-radius: 4px;
                margin: 2px;
                display: inline-block;
                font-size: 0.9em;
            }
            .game-description {
                margin-top: 20px;
            }
            .system-req-container {
                display: flex;
                flex-direction: column;
                margin-top: 20px;
                justify-content: center;
                align-items: center;
            }
            .system-req-columns {
                display: flex;
                gap: 40px;
                flex-wrap: wrap;
            }
            .system-req {
                flex: 1;
                background-color: #1b1b1b;
                padding: 15px;
                border-radius: 8px;
                min-width: 300px;
            }
            .system-req h4 {
                color: white;
                margin-bottom: 10px;
            }
            .edit-buttons {
                margin-top: 15px;
            }
            .edit-buttons button {
                background: #66c0f4;
                color: black;
                border: none;
                padding: 8px 12px;
                margin-right: 5px;
                cursor: pointer;
                border-radius: 4px;
                font-weight: bold;
            }
        </style>
    </head>
    
    <body>
    <?php include("../../includes/publisherHeader.php"); ?>

    <div class="pageWrapper">
        <div class="game-main">
            <!-- LEFT: Video & Thumbnails -->
            <div class="left-side">
                <div class="video-player" id="main-preview">
                    <?php if (!empty($gamePreviews['video'])): ?>
                        <video controls>
                            <source src="<?= htmlspecialchars($gamePreviews['video'][0]['url']) ?>" type="video/mp4">
                            Your browser does not support video playback.
                        </video>
                    <?php elseif (!empty($gamePreviews['image'])): ?>
                        <img src="<?= htmlspecialchars($gamePreviews['image'][0]['url']) ?>" alt="Main Preview">
                    <?php else: ?>
                        <img src="<?= htmlspecialchars($gameDetails['mainPicture']) ?>" alt="Main Preview">
                    <?php endif; ?>
                </div>

                <div class="preview-thumbs">
                    <?php 
                    // Video thumbnails (use poster or fallback image)
                    if (!empty($gamePreviews['video'])) {
                        foreach ($gamePreviews['video'] as $vid) {
                            echo '<img class="thumb" data-type="video" data-src="' . htmlspecialchars($vid['url']) . '" 
                                    src="/PenaconyExchange/assets/image/harmony.png" 
                                    alt="Video Preview">';
                        }
                    }
                    // Image thumbnails
                    if (!empty($gamePreviews['image'])) {
                        foreach ($gamePreviews['image'] as $img) {
                            echo '<img class="thumb" data-type="image" data-src="' . htmlspecialchars($img['url']) . '" 
                                    src="' . htmlspecialchars($img['url']) . '" alt="Preview">';
                        }
                    }
                    ?>
                </div>
            </div>

            <!-- RIGHT: Info & Categories -->
            <div class="right-side">
                <h1><?= htmlspecialchars($gameDetails['gameTitle']) ?></h1>
                <p><strong>Publisher:</strong> <?= htmlspecialchars($gameDetails['username']) ?></p>
                <div class="price">RM<?= number_format($gameDetails['price'], 2) ?></div>
                <div class="categories">
                    <?php foreach ($gameCategories['categories'] as $cat): ?>
                        <span><?= htmlspecialchars($cat['categoryName']) ?></span>
                    <?php endforeach; ?>
                </div>
                <div class="game-description">
                    <?= nl2br(htmlspecialchars($gameDetails['detailedDesc'])) ?>
                </div>
                <div class="edit-buttons">
                    <button onclick="location.href='editGame.php?gameId=<?= $gameId ?>'">Edit Game Info</button>
                    <button onclick="location.href='editImages.php?gameId=<?= $gameId ?>'">Edit Images</button>
                    <button onclick="location.href='editRequirements.php?gameId=<?= $gameId ?>'">Edit Requirements</button>
                </div>
            </div>
        </div>

        <!-- BELOW: Full Description + Requirements -->
        <div class="game-description">
            <h2>About This Game</h2>
            <p><?= nl2br(htmlspecialchars($gameDetails['gameDesc'])) ?></p>
        </div>

        <div class="system-req-container">
            <h2>System Requirements</h2>
            <div class="system-req-columns">
                <div class="system-req">
                    <h4>Minimum:</h4>
                    <?php if (!empty($systemReqs['Minimum'])): ?>
                        <p><strong>OS:</strong> <?= htmlspecialchars($systemReqs['Minimum']['os']) ?></p>
                        <p><strong>Processor:</strong> <?= htmlspecialchars($systemReqs['Minimum']['processor']) ?></p>
                        <p><strong>Memory:</strong> <?= htmlspecialchars($systemReqs['Minimum']['memory']) ?></p>
                        <p><strong>Graphics:</strong> <?= htmlspecialchars($systemReqs['Minimum']['graphic']) ?></p>
                        <p><strong>DirectX:</strong> <?= htmlspecialchars($systemReqs['Minimum']['directX']) ?></p>
                        <p><strong>Storage:</strong> <?= htmlspecialchars($systemReqs['Minimum']['storage']) ?></p>
                        <p><strong>Sound Card:</strong> <?= htmlspecialchars($systemReqs['Minimum']['soundCard']) ?></p>
                    <?php else: ?>
                        <p>No minimum requirements set.</p>
                    <?php endif; ?>
                </div>

                <div class="system-req">
                    <h4>Recommended:</h4>
                    <?php if (!empty($systemReqs['Recommended'])): ?>
                        <p><strong>OS:</strong> <?= htmlspecialchars($systemReqs['Recommended']['os']) ?></p>
                        <p><strong>Processor:</strong> <?= htmlspecialchars($systemReqs['Recommended']['processor']) ?></p>
                        <p><strong>Memory:</strong> <?= htmlspecialchars($systemReqs['Recommended']['memory']) ?></p>
                        <p><strong>Graphics:</strong> <?= htmlspecialchars($systemReqs['Recommended']['graphic']) ?></p>
                        <p><strong>DirectX:</strong> <?= htmlspecialchars($systemReqs['Recommended']['directX']) ?></p>
                        <p><strong>Storage:</strong> <?= htmlspecialchars($systemReqs['Recommended']['storage']) ?></p>
                        <p><strong>Sound Card:</strong> <?= htmlspecialchars($systemReqs['Recommended']['soundCard']) ?></p>
                    <?php else: ?>
                        <p>No recommended requirements set.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <?php include("../../includes/footer.php"); ?>

        <script>    
            document.querySelectorAll('.preview-thumbs .thumb').forEach(thumb => {
                thumb.addEventListener('click', () => {
                    const mainPreview = document.getElementById('main-preview');
                    mainPreview.innerHTML = ''; // Clear old content

                    const type = thumb.getAttribute('data-type');
                    const src = thumb.getAttribute('data-src');

                    if (type === 'video') {
                        const video = document.createElement('video');
                        video.controls = true;
                        video.width = 600;
                        video.height = 400;
                        video.src = src;
                        mainPreview.appendChild(video);
                    } else if (type === 'image') {
                        const img = document.createElement('img');
                        img.src = src;
                        img.alt = 'Main Preview';
                        img.style.width = '100%';
                        img.style.height = '400px';
                        img.style.objectFit = 'cover';
                        mainPreview.appendChild(img);
                    }
                });
            });

        </script>
    </body>
</html>
