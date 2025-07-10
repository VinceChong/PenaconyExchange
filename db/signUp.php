<?php
    include "db.php";

    $username = $_POST["username"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $confirmPassword = $_POST["confirmPassword"];

    if ($password !== $confirmPassword) {
        echo "Password not matched. Please try again";
        exit;
    }

    $encrypted = password_hash($password, PASSWORD_DEFAULT);
    $query = "INSERT INTO User (username, email, password) VALUES (?, ?, ?)";
    $statement = $connect->prepare($query);
    $statement->bind_param("sss", $username, $email, $encrypted);

    if ($statement->execute()){
        header("Location: ../index.php?success=".urlencode("Sign up successful! You can now log in."));
        exit;
    } else {
        $message = "Error: ".$statement->error;
        header("Location: ../index.php?error=".urlencode($message));
        exit;
    }
?>