<?php
    session_start();
    include "db.php";

    if (!isset($_SESSION["user"]["userId"])) {
        echo json_encode(["success" => false, "message" => "User not logged in"]);
        exit;
    }

    $userId = $_SESSION["user"]["userId"];

    if (!isset($connect)) {
        echo json_encode(["success" => false, "message" => "Database connection error"]);
        exit;
    }

    if (isset($_FILES["profilePicture"]) && $_FILES["profilePicture"]["error"] === 0) {
        $uploadDir = __DIR__ . "/assets/profile/";
        $publicPath = "/PenaconyExchange/db/assets/profile/";
        $fileName = time() . "_" . basename($_FILES["profilePicture"]["name"]);
        $targetFilePath = $uploadDir . $fileName;
        $path = $publicPath . $fileName;

        $fileType = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        $allowedTypes = ["jpg", "jpeg", "png", "gif", "webp"];

        if (!in_array($fileType, $allowedTypes)) {
            echo json_encode(["success" => false, "message" => "Invalid file type"]);
            exit;
        }

        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        // Get old profile picture path
        $oldQuery = $connect->prepare("SELECT profilePicture FROM User WHERE userId = ?");
        $oldQuery->bind_param("i", $userId);
        $oldQuery->execute();
        $oldQuery->bind_result($oldProfilePath);
        $oldQuery->fetch();
        $oldQuery->close();

        // Remove old file if it's not N/A or default
        if ($oldProfilePath !== "N/A" && basename($oldProfilePath) !== "default.jpg") {
            $oldFilePath = __DIR__ . str_replace("/PenaconyExchange/db", "", $oldProfilePath);
            if (file_exists($oldFilePath)) {
                unlink($oldFilePath);
            }
        }

        if (move_uploaded_file($_FILES["profilePicture"]["tmp_name"], $targetFilePath)) {
            $stmt = $connect->prepare("UPDATE User SET profilePicture = ? WHERE userId = ?");
            $stmt->bind_param("si", $path, $userId);

            if ($stmt->execute()) {
                $_SESSION["user"]["profilePicture"] = $path;
                echo json_encode(["success" => true, "message" => "Profile picture updated", "path" => $path]);
            } else {
                echo json_encode(["success" => false, "message" => "Database update failed"]);
            }

            $stmt->close();
        } else {
            echo json_encode(["success" => false, "message" => "Failed to move uploaded file"]);
        }
    } else {
        echo json_encode(["success" => false, "message" => "No file uploaded or upload error"]);
    }
?>
