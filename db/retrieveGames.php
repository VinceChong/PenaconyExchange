<?php
    include "db.php";

    $query = "SELECT gameId, gameTitle, mainPicture FROM Game";
    $result = $connect -> query($query);

    $games = [];

    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $games[] = $row;
        }
    }

    header("Content-Type: application/json");
    echo json_encode($games);
?>