/** havent finished */
<?php
    include "db.php";

    $conn = mysqli_connect("localhost", "username", "password", "database");
    $userId = $_SESSION['userId'];

    $username = $_POST['username'];
    $gmail = $_POST['gmail'];

    $currentPassword = $_POST['currentPassword'];
    $newPassword = $_POST['newPassword'];
    $confirmNewPassword = $_POST['confirmNewPassword'];

    // Handle avatar upload
    $avatarPath = "";
    if ($_FILES['avatar']['error'] == UPLOAD_ERR_OK) {
        $targetDir = "db/image/avatar/";
        if (!file_exists($targetDir)) {
            mkdir($targetDir, 0777, true);
        }

        $filename = basename($_FILES["avatar"]["name"]);
        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        $newFilename = "user_" . $userId . "." . $ext;
        $targetFile = $targetDir . $newFilename;

        if (move_uploaded_file($_FILES["avatar"]["tmp_name"], $targetFile)) {
            $avatarPath = $targetFile;
        }
    }

    // Update profile (username, gmail, avatar)
    $updateSql = "UPDATE Users SET username=?, gmail=?" . ($avatarPath ? ", avatarPath=?" : "") . " WHERE userId=?";
    $stmt = mysqli_prepare($conn, $updateSql);

    if ($avatarPath) {
        mysqli_stmt_bind_param($stmt, "sssi", $username, $gmail, $avatarPath, $userId);
    } else {
        mysqli_stmt_bind_param($stmt, "ssi", $username, $gmail, $userId);
    }
    mysqli_stmt_execute($stmt);

    // Handle password change (optional)
    if (!empty($currentPassword) && !empty($newPassword) && !empty($confirmNewPassword)) {
        if ($newPassword !== $confirmNewPassword) {
            die("New passwords do not match.");
        }

        // Fetch existing password
        $stmt = mysqli_prepare($conn, "SELECT password FROM Users WHERE userId = ?");
        mysqli_stmt_bind_param($stmt, "i", $userId);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $hashedPassword);
        mysqli_stmt_fetch($stmt);
        mysqli_stmt_close($stmt);

        if (password_verify($currentPassword, $hashedPassword)) {
            $newHashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
            $stmt = mysqli_prepare($conn, "UPDATE Users SET password = ? WHERE userId = ?");
            mysqli_stmt_bind_param($stmt, "si", $newHashedPassword, $userId);
            mysqli_stmt_execute($stmt);
        } else {
            die("Current password is incorrect.");
        }
    }

    echo "Profile updated successfully.";
    echo "<br><a href='profile.php'>Back to Profile</a>";
?>
