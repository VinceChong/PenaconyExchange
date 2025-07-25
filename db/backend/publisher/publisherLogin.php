<?php
    session_start();
    include "../db.php";

    $email = $_POST["email"];
    $password = $_POST["password"];

    $query = "SELECT * FROM Publisher WHERE email = ?";
    $statement = $connect->prepare($query);
    $statement->bind_param("s", $email);
    $statement->execute();
    $result = $statement->get_result();

    if ($publisher = $result->fetch_assoc()) {
        echo "Input password: " . $password . "<br>";
        echo "Hashed in DB: " . $publisher["password"] . "<br>";

        if(password_verify($password, $publisher["password"])){
            $_SESSION["publisher"] = $publisher;
            header("Location: /PenaconyExchange/pages/publisher/publisherHome.php");
            exit;
        } else {
            header("Location: /PenaconyExchange/publisherLogin.php?error=".urlencode("Invalid password, please try again"));
            exit;
        }
    } else {
        header("Location: /PenaconyExchange/publisherLogin.php?error=".urlencode("No publisher found. Please try again"));
        exit;
    }
?>