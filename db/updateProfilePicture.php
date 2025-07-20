<?php
session_start();
include "db.php";

$userId = $_SESSION["user"]["userId"];

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["profilePicture"])) {
    $targetDir = "/PenaconyExchange/db/image/profile/";
    $fileName = basename($_FILES["profilePicture"]["name"]);
    $targetFile = $targetDir . $fileName;

    // Validate file type
    $allowedTypes = ["image/jpeg", "image/png", "image/jpg", "image/gif"];
    if (!in_array($_FILES["profilePicture"]["type"], $allowedTypes)) {
        echo "<script>alert('Only image files are allowed (JPG, PNG, GIF).'); window.history.back();</script>";
        exit;
    }

    // Move uploaded file
    if (move_uploaded_file($_FILES["profilePicture"]["tmp_name"], $targetFile)) {
        // Update database
        $relativePath = "/PenaconyExchange/db/image/profile/" . $fileName;
        $query = "UPDATE User SET profilePicture = ? WHERE userId = ?";
        $statement = $connect->prepare($query);
        $statement->bind_param("si", $relativePath, $userId);

        if ($statement->execute()) {
            // Update session data
            $_SESSION["user"]["profilePicture"] = $relativePath;

            echo "<script>alert('Profile picture updated successfully.'); window.location.href='/PenaconyExchange/pages/profile.php';</script>";
        } else {
            echo "<script>alert('Failed to update profile picture in the database.'); window.history.back();</script>";
        }
    } else {
        echo "<script>alert('Failed to upload the image.'); window.history.back();</script>";
    }
} else {
    echo "<script>alert('No image uploaded.'); window.history.back();</script>";
}
?>
