<?php
    function getUserProfile($connect, $gameId) {
        $query = "SELECT username, gmail, avatarPath FROM Users WHERE userId = ?";

        $statement = $connect->prepare($query);
        $statement->bind_param("i", $gameId);
        $statement->execute();
        $result = $statement->get_result();
        return $result->fetch_assoc();
    }
?>
