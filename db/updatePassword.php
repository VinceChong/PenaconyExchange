<?php
    session_start();
    include "db.php";

    if (!isset($_SESSION["user"])) {
        die("User not logged in");
    }

    $userId = $_SESSION["user"]["userId"];
    $currentPassword = trim($_POST["currentPassword"]);
    $newPassword = trim($_POST["newPassword"]);
    $confirmNewPassword = trim($_POST["confirmNewPassword"]);

    if (!empty($newPassword)) {
        $stmt = $connect->prepare("SELECT password FROM User WHERE userId = ?");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $stmt->bind_result($storedHash);
        $stmt->fetch();
        $stmt->close();

        if (password_verify($currentPassword, $storedHash)) {
            if ($newPassword === $confirmNewPassword && strlen($newPassword) >= 8) {
                $newHash = password_hash($newPassword, PASSWORD_DEFAULT);
                $stmt = $connect->prepare("UPDATE User SET password = ? WHERE userId = ?");
                $stmt->bind_param("si", $newHash, $userId);
                $stmt->execute();
                $stmt->close();
                echo json_encode(["success" => true, "newPassword" => $newPassword]);
                exit();
            }
        }
    }

    if (empty($newPassword)) {
        echo json_encode(["success" => false, "error" => "New password cannot be empty"]);
        exit();
    }

    if ($newPassword !== $confirmNewPassword) {
        echo json_encode(["success" => false, "error" => "Passwords do not match"]);
        exit();
    }

    if (strlen($newPassword) < 8) {
        echo json_encode(["success" => false, "error" => "Password must be at least 8 characters"]);
        exit();
    }

?>
