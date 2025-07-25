<?php
    session_start();
    include "../db.php";

    if (!isset($_SESSION["publisher"])) {
        die("Publisher not logged in");
    }

    $publisherId = $_SESSION["publisher"]["publisherId"];
    $newEmail = trim($_POST["email"]);

    if (!empty($newEmail)) {
        $stmt = $connect->prepare("UPDATE Publisher SET email = ? WHERE publisherId = ?");
        $stmt->bind_param("si", $newEmail, $publisherId);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            $_SESSION["publisher"]["email"] = $newEmail;
            echo json_encode(["success" => true, "newEmail" => $newEmail]);
        } else {
            echo json_encode(["success" => false, "error" => "Email unchanged"]);
        }

        $stmt->close();
        exit();
    }

    echo json_encode(["success" => false, "error" => "Email cannot be empty"]);
?>
