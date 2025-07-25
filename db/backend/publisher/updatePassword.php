<?php
    session_start();
    include "../db.php";

    if (!isset($_SESSION["publisher"])) {
        die("Publisher not logged in");
    }

    $publisherId = $_SESSION["publisher"]["publisherId"];
    $currentPassword = trim($_POST["currentPassword"]);
    $newPassword = trim($_POST["newPassword"]);
    $confirmNewPassword = trim($_POST["confirmNewPassword"]);

    if (!empty($newPassword)) {
        $stmt = $connect->prepare("SELECT password FROM Publisher WHERE publisherId = ?");
        $stmt->bind_param("i", $publisherId);
        $stmt->execute();
        $stmt->bind_result($storedHash);
        $stmt->fetch();
        $stmt->close();

        if (password_verify($currentPassword, $storedHash)) {
            if ($newPassword === $confirmNewPassword && strlen($newPassword) >= 8) {
                $newHash = password_hash($newPassword, PASSWORD_DEFAULT);
                $stmt = $connect->prepare("UPDATE Publisher SET password = ? WHERE publisherId = ?");
                $stmt->bind_param("si", $newHash, $publisherId);
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
