<?php
    session_start();
    include "db.php";

    if (!isset($_SESSION["user"])) {
        die("User not logged in");
    }

    $userId = $_SESSION["user"]["userId"];
    $newUsername = trim($_POST["username"]);

    if (!empty($newUsername)) {
        $stmt = $connect->prepare("UPDATE User SET username = ? WHERE userId = ?");
        $stmt->bind_param("si", $newUsername, $userId);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            $_SESSION["user"]["username"] = $newUsername;
            echo json_encode(["success" => true, "newUsername" => $newUsername]);
        } else {
            echo json_encode(["success" => false, "error" => "Username unchanged"]);
        }

        $stmt->close();
        exit();
    }

    echo json_encode(["success" => false, "error" => "Username cannot be empty"]);
?>
