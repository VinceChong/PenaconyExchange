<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION["user"])) {
    header("Location: /PenaconyExchange/pages/authentication.php");
    exit;
}

include "../db/backend/db.php";
include "../db/backend/retrieveGameDetail.php";

    $gameId = $_GET['gameId'] ?? 0;
    $gameDetails = retrieveGameDetails($connect, $gameId);
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
    <title><?php echo $title; ?> | Purchased Game</title>
    <link rel="stylesheet" href="/PenaconyExchange/styles/common.css"/>
    <link rel="stylesheet" href="/PenaconyExchange/styles/purchasedGame.css?v=2"/>
    <link rel="icon" href="/PenaconyExchange/assets/image/harmony.png">
    <style>
            body {
                background: #0f4c81;
                color: #c7d5e0;
                font-family: Arial, sans-serif;
            }
            .pageWrapper {
                max-width: 1200px;
                margin: 0 auto;
                padding: 50px;
                padding-top: 80px; /* header height + spacing */
                background: #0f4c81;
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
            .gameCard {
                display: flex;
                flex-direction: column;
                align-items: center;
                margin: 10px;
            }

            .installBtn {
                margin-top: 8px;
                padding: 6px 12px;
                background: #1e90ff;
                color: white;
                border: none;
                border-radius: 4px;
            }

            .installBtn:hover {
                background: #0d6efd;
            }
            .comment {
                background: #1b2330;
                padding: 16px;
                border-radius: 8px;
                max-width: 700px;
                margin: 20px;
            }
            .comment h3 {
            margin: 0 0 10px;
            color: #e6eef8;
            }
            .star-rating {
            display: flex;
            flex-direction: row-reverse;
            width: max-content;
            }
            .star-rating input {
            display: none; /* hide radios; keep them accessible via labels */
            }
            .star-rating label {
            color: #555e6d; /* default */
            }
            .star-rating label:hover,
            .star-rating label:hover ~ label {
            color: #ffcc33; /* hover fill */
            }
            .star-rating input:checked ~ label {
            color: #ffcc33; /* selected fill */
            }
            textarea[name="commentDesc"] {
            width: 90%;
            margin-top: 12px;
            padding: 10px;
            border-radius: 6px;
            border: 1px solid #2b3546;
            background: #0f1622;
            color: #e6eef8;
            }
            .comment-actions {
            display: flex;
            justify-content: space-between;
            margin-top: 10px;
            }
           
            .comment button[type="submit"] {
            background: #7fb3ff;
            border: none;
            color: #0b1220;
            padding: 8px 14px;
            border-radius: 6px;
            font-weight: bold;
            }
        </style>
</head>
<body>
<?php include("../includes/header.php"); ?>

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
                                    src="'. htmlspecialchars($vid['thumbnail']) . '"
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
                <div class="categories">
                    <?php foreach ($gameCategories['categories'] as $cat): ?>
                        <span><?= htmlspecialchars($cat['categoryName']) ?></span>
                    <?php endforeach; ?>
                </div>
                <div class="game-description">
                    <?= nl2br(htmlspecialchars($gameDetails['detailedDesc'])) ?>
                </div>
                <!-- Full Description -->
                <div class="game-description">
                    <h2>About</h2>
                    <p><?= nl2br(htmlspecialchars($gameDetails['gameDesc'])) ?></p>
                </div>
                <div class="gameCard">
                    <button class="installBtn" onclick="installGame('<?php echo htmlspecialchars($gameDetails['gameTitle']); ?>')">
                        Install
                    </button>
                </div>
            </div>
        </div>
        <!-- Comment Section -->
        <form class="comment" action="comment.php" method="post">
        <input type="hidden" name="gameId" value="<?= htmlspecialchars($gameDetails['gameId'] ?? '') ?>">

        <h3>Write a Comment for <?= htmlspecialchars($gameDetails['gameTitle'] ?? 'this game', ENT_QUOTES, 'UTF-8') ?></h3>


        <!-- Stars (accessible radio buttons styled as stars) -->
        <div class="star-rating" aria-label="Rating">
            <!-- Note: row-reverse lets us use the sibling selector for hover/checked -->
            <input type="radio" id="r5" name="rating" value="5"><label for="r5">★</label>
            <input type="radio" id="r4" name="rating" value="4"><label for="r4">★</label>
            <input type="radio" id="r3" name="rating" value="3"><label for="r3">★</label>
            <input type="radio" id="r2" name="rating" value="2"><label for="r2">★</label>
            <input type="radio" id="r1" name="rating" value="1"><label for="r1">★</label>
        </div>

        <textarea name="commentDesc" rows="5" placeholder="Share what you liked or disliked…" maxlength="2000" required></textarea>

        <div class="comment-actions">
            <span aria-live="polite"></span>
            <button type="submit">Post comment</button>
        </div>
        </form>
    </div>
</div>

<?php include("../includes/footer.php"); ?>
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

            function installGame(title) {
                alert("Install " + title + " successfully ✅");
            }
        </script>
</body>
</html>
