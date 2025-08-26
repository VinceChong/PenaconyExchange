<?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    if (!isset($_SESSION["publisher"])) {
        header("Location: /PenaconyExchange/pages/publisher/publisherLogin.php");
        exit;
    }
    include "../../db/backend/db.php"; 

    // Fetch categories from DB
    $categories = [];
    $sql = "SELECT categoryId, categoryName FROM Category";
    $result = mysqli_query($connect, $sql);
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $categories[] = $row;
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
        <title>Add Game</title>
        <link rel="stylesheet" href="/PenaconyExchange/styles/common.css"/>
        <link rel="stylesheet" href="/PenaconyExchange/styles/addGame.css"/>
        <link rel="icon" href="/PenaconyExchange/assets/image/harmony.png">
        <style>
            * { box-sizing: border-box; }

            body {
                font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
                margin: 0;
                padding: 0;
            }

            #mainContainer { max-width: 900px; margin: 30px auto; }

            form {
                background: #fff;
                padding: 30px;
                border-radius: 12px;
                transition: transform .2s ease;
            }
            form:hover { transform: translateY(-2px); }

            form h3 { margin: 0 0 10px; font-size: 20px; color: #0f4c81; }

            form label {
                display: block;
                margin-top: 15px;
                color: #0f4c81;
                font-weight: 700;
            }

            form input,
            form textarea,
            form select {
                width: 100%;
                padding: 10px 12px;
                margin-top: 6px;
                border-radius: 8px;
                border: 1px solid #ccc;
                font-size: 14px;
                transition: all .2s;
            }

            form input:focus,
            form textarea:focus,
            form select:focus {
                border-color: #0f4c81;
                outline: none;
            }

            form input[type="file"] {
                border: none;
                padding: 5px;
                color: #0f4c81;
            }

            .form-section {
                margin-bottom: 25px;
                padding-bottom: 20px;
                border-bottom: 1px solid #e0e0e0;
            }
            .form-section:last-of-type { border-bottom: none; }

            /* Submit button (scoped so category buttons aren't affected) */
            #addGameForm > button[type="submit"] {
                display: block;
                margin: 15px auto 0;
                background: #0f4c81;
                border: none;
                padding: 12px 25px;
                border-radius: 8px;
                cursor: pointer;
                font-weight: bold;
                color: #fff;
                font-size: 16px;
                transition: all .2s;
            }
            #addGameForm > button[type="submit"]:hover {
                background: #3a8ac4;
                transform: translateY(-2px);
            }

            /* Preview styles */
            .preview-title {
                margin-top: 12px;
                font-size: 14px;
                color: #0f4c81;
                font-weight: 700;
            }

            .thumb-grid,
            .video-grid {
                display: grid;
                gap: 12px;
                margin-top: 10px;
            }
            .thumb-grid { grid-template-columns: repeat(auto-fill, minmax(110px, 1fr)); }
            .video-grid { grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); }

            .thumb {
                border: 1px solid #e5e5e5;
                border-radius: 8px;
                padding: 8px;
                text-align: center;
                overflow: hidden;
            }
            .thumb img {
                max-width: 100%;
                max-height: 120px;
                object-fit: cover;
                display: block;
                margin: 0 auto 6px;
                border-radius: 6px;
            }
            .thumb video {
                width: 100%;
                max-height: 180px;
                display: block;
                border-radius: 6px;
            }
            .thumb .meta { font-size: 12px; color: #444; word-break: break-all; }

            .mainpic-preview {
                margin-top: 10px;
                max-width: 280px;
                border-radius: 10px;
                border: 1px solid #e5e5e5;
                display: block;
            }

            /* Category buttons */
            .cat-grid { 
                display: flex; 
                flex-wrap: wrap; 
                gap: 8px; 
                margin-top: 8px; 
            }

            .cat-btn {
                display: inline-flex;
                align-items: center;
                padding: 8px 12px;
                border-radius: 999px;
                border: 1px solid #cfe3f8;
                background: #f6faff;
                color: #0f4c81;
                font-size: 13px;
                cursor: pointer;
                transition: transform .15s ease, background .15s ease, border-color .15s ease;
            }
            .cat-btn:hover { 
                transform: translateY(-1px); 
            }
            .cat-btn.active {
                background: #0f4c81;
                color: #fff;
                border-color: #0f4c81;
            }
            .cat-btn:focus-visible { 
                outline: 2px solid #3a8ac4; 
                outline-offset: 2px; 
            }

            /* Selected chips */
            .chips { 
                display: flex; 
                flex-wrap: wrap; 
                gap: 8px; 
                margin-top: 10px; 
            }
            .chip {
                background: #e9f2fb;
                color: #0f4c81;
                padding: 6px 10px;
                border-radius: 999px;
                font-size: 12px;
                border: 1px solid #cfe3f8;
            }
            .chip.removable { 
                cursor: pointer; 
                user-select: none; 
            }
            .chip.removable::after { 
                content: " ✕"; 
                font-weight: 600; 
                margin-left: 4px; 
            }

            small.helper { 
                color: #555; 
                display: block; 
                margin-top: 6px; 
            }

            /* System requirements layout */
            .sr-grid {
                display: grid;
                gap: 16px;
                grid-template-columns: 1fr;
                margin-top: 10px;
            }
            @media (min-width: 900px) {
                .sr-grid { grid-template-columns: 1fr 1fr; }
            }
            .sr-card {
                border: 1px solid #e0e0e0;
                border-radius: 12px;
                padding: 16px;
                background: #fafcff;
            }
            .sr-card legend {
                padding: 0 8px;
                font-weight: 700;
                color: #0f4c81;
            }
            .sr-copy-btn {
                margin-top: 14px;
                background: #e9f2fb;
                color: #0f4c81;
                border: 1px solid #cfe3f8;
                padding: 8px 12px;
                border-radius: 8px;
                cursor: pointer;
                font-weight: 600;
            }
            .sr-copy-btn:hover { 
                transform: translateY(-1px); 
            }

            </style>

    </head>
    <body>
        <?php include("../../includes/publisherHeader.php"); ?>
        <div class="pageWrapper">
            <div class="pageContent">
                <div class="gamesWrapper">
                    <div id="mainContainer">
                        <h2 style="color:white;">Add New Game</h2>

                        <form action="/PenaconyExchange/db/backend/publisher/insertGame.php" method="POST" enctype="multipart/form-data" id="addGameForm">
                        <!-- Basic Info -->
                            <div class="form-section">
                                <label for="title">Game Title</label>
                                <input type="text" id="title" name="title" required>

                                <label for="desc">Game Description</label>
                                <textarea id="desc" name="desc" rows="3" required></textarea>

                                <label for="detailDesc">Detailed Description</label>
                                <textarea id="detailDesc" name="detailDesc" rows="6"></textarea>

                                <label for="mainPic">Main Picture</label>
                                <input type="file" id="mainPic" name="mainPic" accept="image/*" required>
                                <img id="mainPicPreview" class="mainpic-preview" style="display:none;" alt="Main picture preview">

                                <label for="price">Price (RM)</label>
                                <input type="number" id="price" name="price" step="0.01" min="0" required>

                                <label for="releaseDate">Release Date</label>
                                <input type="date" id="releaseDate" name="releaseDate" required>

                                <!-- MULTI-CATEGORY -->
                                <label>Categories</label>
                                <div id="categoriesBtnGrid" class="cat-grid">
                                <?php foreach ($categories as $cat): ?>
                                    <button type="button"
                                            class="cat-btn"
                                            data-id="<?= (int)$cat['categoryId'] ?>"
                                            aria-pressed="false">
                                    <?= htmlspecialchars($cat['categoryName']) ?>
                                    </button>
                                <?php endforeach; ?>
                                </div>

                                <!-- Hidden inputs for selected categories will be injected here -->
                                <div id="categoriesHidden"></div>

                                <div class="preview-title">Selected Categories</div>
                                <div id="selectedCategories" class="chips"></div>
                            </div>

                            <!-- Previews -->
                            <div class="form-section">
                                <label for="videos">Video(s)</label>
                                <input type="file" id="videos" name="videos[]" accept="video/mp4,video/webm,video/ogg" multiple>
                                <div class="preview-title">Video Preview(s)</div>
                                <div id="videosPreview" class="video-grid"></div>

                                <label for="videoThumbs">Thumbnail(s) for Video(s)</label>
                                <input type="file" id="videoThumbs" name="videoThumbs[]" accept="image/*" multiple>
                                <div class="preview-title">Video Thumbnail Preview(s)</div>
                                <div id="videoThumbsPreview" class="thumb-grid"></div>

                                <label for="images">Image(s)</label>
                                <input type="file" id="images" name="images[]" accept="image/*" multiple>
                                <div class="preview-title">Image Preview(s)</div>
                                <div id="imagesPreview" class="thumb-grid"></div>
                            </div>

                            <!-- System Requirements -->
                            <div class="form-section" id="systemRequirements">
                                <h3>System Requirements</h3>

                                <div class="sr-grid">
                                    <!-- Minimum -->
                                    <fieldset class="sr-card">
                                    <legend>Minimum</legend>

                                    <label for="minOs">OS</label>
                                    <input type="text" id="minOs" name="minOs">

                                    <label for="minProcessor">Processor</label>
                                    <input type="text" id="minProcessor" name="minProcessor">

                                    <label for="minMemory">Memory</label>
                                    <input type="text" id="minMemory" name="minMemory">

                                    <label for="minGraphic">Graphics</label>
                                    <input type="text" id="minGraphic" name="minGraphic">

                                    <label for="minDirectX">DirectX</label>
                                    <input type="text" id="minDirectX" name="minDirectX">

                                    <label for="minStorage">Storage</label>
                                    <input type="text" id="minStorage" name="minStorage">

                                    <label for="minSoundCard">Sound Card</label>
                                    <input type="text" id="minSoundCard" name="minSoundCard">
                                    </fieldset>

                                    <!-- Recommended -->
                                    <fieldset class="sr-card">
                                    <legend>Recommended</legend>

                                    <label for="recOs">OS</label>
                                    <input type="text" id="recOs" name="recOs">

                                    <label for="recProcessor">Processor</label>
                                    <input type="text" id="recProcessor" name="recProcessor">

                                    <label for="recMemory">Memory</label>
                                    <input type="text" id="recMemory" name="recMemory">

                                    <label for="recGraphic">Graphics</label>
                                    <input type="text" id="recGraphic" name="recGraphic">

                                    <label for="recDirectX">DirectX</label>
                                    <input type="text" id="recDirectX" name="recDirectX">

                                    <label for="recStorage">Storage</label>
                                    <input type="text" id="recStorage" name="recStorage">

                                    <label for="recSoundCard">Sound Card</label>
                                    <input type="text" id="recSoundCard" name="recSoundCard">
                                    </fieldset>
                                </div>
                            </div>

                        <button type="submit">Add Game</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <?php include("../../includes/footer.php"); ?>

    <script src="/PenaconyExchange/scripts/publisher/addGame.js" defer></script>
    </body>
</html>
