<?php
    session_start();
    include "../db.php";

    if (!isset($_SESSION["publisher"])) {
        die("Publisher not logged in");
    }

    $publisherId = $_SESSION["publisher"]["publisherId"];
    $newUsername = trim($_POST["username"]);

    if (!empty($newUsername)) {
        $stmt = $connect->prepare("UPDATE Publisher SET username = ? WHERE publisherId = ?");
        $stmt->bind_param("si", $newUsername, $publisherId);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            $_SESSION["publisher"]["username"] = $newUsername;
            echo json_encode(["success" => true, "newUsername" => $newUsername]);
        } else {
            echo json_encode(["success" => false, "error" => "Username unchanged"]);
        }

        $stmt->close();
        exit();
    }

    echo json_encode(["success" => false, "error" => "Username cannot be empty"]);
?>
