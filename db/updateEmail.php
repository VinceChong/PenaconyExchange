<?php
    session_start();
    include "db.php";

    if (!isset($_SESSION["user"])) {
        die("User not logged in");
    }

    $userId = $_SESSION["user"]["userId"];
    $newEmail = trim($_POST["email"]);

    if (!empty($newEmail)) {
        $stmt = $connect->prepare("UPDATE User SET email = ? WHERE userId = ?");
        $stmt->bind_param("si", $newEmail, $userId);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            $_SESSION["user"]["email"] = $newEmail;
            echo json_encode(["success" => true, "newEmail" => $newEmail]);
        } else {
            echo json_encode(["success" => false, "error" => "Email unchanged"]);
        }

        $stmt->close();
        exit();
    }

    echo json_encode(["success" => false, "error" => "Email cannot be empty"]);
?>
