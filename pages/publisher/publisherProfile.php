<?php
    session_start();

    // Get publisher data
    $publisher = $_SESSION["publisher"];
    $publisherId = $publisher["publisherId"];
    $username = $publisher["username"];
    $email = $publisher["email"];
    $logo = $publisher["logo"];

    if($logo === "N/A" || empty($logo)){
        $logo = "/PenaconyExchange/db/assets/logo/default.jpg";
    }
?>

<!DOCTYPE html>

<html lang = "en">
    <head>
        <meta charset="UTF-8"/>
        <meta name = "viewport" content = "width=device-width, initial-scale=1.0"/>
        <title> Profile </title>
        <link rel = "stylesheet" href = "/PenaconyExchange/styles/common.css"/>
        <link rel = "stylesheet" href = "/PenaconyExchange/styles/profile.css"/>
        <link rel = "icon" href = "/PenaconyExchange/assets/image/harmony.png">
    </head>

    <body>
        <?php include("../../includes/publisherHeader.php"); ?>

        <div class = "pageWrapper">
            <div class="pageContent">
                <div class = "profileContainer">
                    <form action="/PenaconyExchange/db/backend/publisher/updateProfileLogo.php" method="POST" enctype="multipart/form-data">
                        <div class="profileCard">
                            <label for="profilePictureInput">
                                <img src="<?php echo $logo; ?>" class="profilePicture" id="profilePicturePreview">
                            </label>
                            <input type="file" name="profilePicture" id="profilePictureInput" accept="image/*" style="display:none;" onchange="previewImage(event)">
                            
                            <div class = "profileDesc">
                                <p id = "username"> Publisher Name: <?php echo htmlspecialchars($username); ?></p>
                                <p id = "email"> Email: <?php echo htmlspecialchars($email); ?></p>
                            </div>
                        </div>
                    </form>
                    
                    <div class="buttonGroup">
                        <button type="button" onclick="toggleSection('usernameForm')">Modify Publisher Name</button>
                        <button type="button" onclick="toggleSection('emailForm')">Modify Publisher Email</button>
                        <button type="button" onclick="toggleSection('passwordForm')">Modify Publisher Password</button>
                    </div>


                    <form action="/PenaconyExchange/db/backend/logout.php" method="POST">
                        <button type="submit">Logout</button>
                    </form>

                    <!-- Publisher name form -->
                    <div class="formContainer hidden" id="usernameForm">
                        <div id="usernameModalOverlay" class="modal-overlay hidden">
                            <div class="modal-box" onclick="event.stopPropagation()">
                                <form action="/PenaconyExchange/db/backend/publisher/updateUsername.php" method="POST">
                                    <h3> Change Publisher Name </h3>
                                    <label> New Publisher Name </label>
                                    <input type="text" name="username" placeholder="Enter new publisher name" required>
                                    <button type="submit"> Update Publisher Name </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Email form -->
                    <div class="formContainer hidden" id="emailForm">
                        <div id="emailModalOverlay" class="modal-overlay hidden">
                            <div class="modal-box" onclick="event.stopPropagation()">
                                <form action="/PenaconyExchange/db/backend/publisher/updateEmail.php" method="POST">
                                    <h3> Change Publisher Email </h3>
                                    <label> New Publisher Password </label>
                                    <input type="email" name="email" placeholder="Enter new publisher email" required>
                                    <button type="submit"> Update Publisher Email </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Password form -->
                    <div class="formContainer hidden" id="passwordForm">
                        <div id="passwordModalOverlay" class="modal-overlay hidden">
                            <div class="modal-box" onclick="event.stopPropagation()">
                                <form action="/PenaconyExchange/db/backend/publisher/updatePassword.php" method="POST">
                                    <h3>Change Password</h3>
                                    <label> Current Password </label>
                                    <input type="password" name="currentPassword" required>
                                    <label> New Password </label>
                                    <input type="password" name="newPassword" required>
                                    <label> Confirm New Password </label>
                                    <input type="password" name="confirmNewPassword" required>
                                    <button type="submit"> Update Password </button>
                                </form>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        
        <?php include("../includes/footer.php"); ?>
        <script src = "/PenaconyExchange/scripts/publisher/profile.js"></script>
    </body>
</html>