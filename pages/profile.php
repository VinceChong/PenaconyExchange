<?php
    session_start();

    // Get user data
    $user = $_SESSION["user"];
    $userId = $user["userId"];
    $username = $user["username"];
    $email = $user["email"];
    $profilePicture = $user["profilePicture"];

    if($profilePicture === "N/A" || empty($profilePicture)){
        $profilePicture = "/PenaconyExchange/db/image/profile/default.jpg";
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
        <?php include("../includes/header.php"); ?>
        
        <div class = "pageWrapper">
            <div class="pageContent">
                <div class = "profileContainer">
                    <form action="/PenaconyExchange/db/updateProfilePicture.php" method="POST" enctype="multipart/form-data">
                        <div class="profileCard">
                            <label for="profilePictureInput">
                                <img src="<?php echo $profilePicture; ?>" class="profilePicture" id="profilePicturePreview">
                            </label>
                            <input type="file" name="profilePicture" id="profilePictureInput" accept="image/*" style="display:none;" onchange="previewImage(event)">
                            
                            <div class = "profileDesc">
                                <p id = "username"> Username: <?php echo htmlspecialchars($username); ?></p>
                                <p id = "email"> Email: <?php echo htmlspecialchars($email); ?></p>
                            </div>
                        </div>
                    </form>

                    <form action="/PenaconyExchange/db/updateProfile.php" method="POST" enctype="multipart/form-data">
                        <div class="formGroup">
                            <button type="button" onclick="toggleSection('usernameForm')">Modify Username</button>
                            <button type="button" onclick="toggleSection('emailForm')">Modify Email</button>
                            <button type="button" onclick="toggleSection('passwordForm')">Modify Password</button>
                            <button type="submit">Save Changes</button>
                        </div>
                    </form>

                    <form action="/PenaconyExchange/db/logout.php" method="POST">
                        <button type="submit">Logout</button>
                    </form>

                    <!-- Username Form -->
                    <div class="profile-card hidden" id="usernameForm">
                        <form action="updateProfile.php" method="POST">
                            <label>New Username:</label>
                            <input type="text" name="username" required>
                            <button type="submit">Update Username</button>
                        </form>
                    </div>

                    <!-- Email Form -->
                    <div class="profile-card hidden" id="emailForm">
                        <form action="updateProfile.php" method="POST">
                            <label>New Email:</label>
                            <input type="email" name="gmail" required>
                            <button type="submit">Update Email</button>
                        </form>
                    </div>

                    <!-- Password Form -->
                    <div class="profile-card hidden" id="passwordForm">
                        <form action="updateProfile.php" method="POST">
                            <label>Current Password:</label>
                            <input type="password" name="currentPassword" required>
                            <label>New Password:</label>
                            <input type="password" name="newPassword" required>
                            <label>Confirm New Password:</label>
                            <input type="password" name="confirmNewPassword" required>
                            <button type="submit">Update Password</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        
        <?php include("../includes/footer.php"); ?>
        <script src = "/PenaconyExchange/scripts/profile.js"></script>
    </body>
</html>