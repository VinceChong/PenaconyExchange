<?php
    session_start();
    include "db.php";

    $email = $_POST["email"];
    $password = $_POST["password"];

    $query = "SELECT * FROM User WHERE email = ?";
    $statement = $connect->prepare($query);
    $statement->bind_param("s", $email);
    $statement->execute();
    $result = $statement->get_result();

    if ($user = $result->fetch_assoc()) {
        echo "Input password: " . $password . "<br>";
        echo "Hashed in DB: " . $user["password"] . "<br>";

        if(password_verify($password, $user["password"])){
            $_SESSION["user"] = $user;
            header("Location: /PenaconyExchange/index.php");
            exit;
        } else {
            header("Location: /PenaconyExchange/pages/authentication.php?error=".urlencode("Invalid password, please try again"));
            exit;
        }
    } else {
        header("Location: /PenaconyExchange/pages/authentication.php?error=".urlencode("No user found. Please try again"));
        exit;
    }
?>