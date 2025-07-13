<?php
    include "db.php";

    $gameId = $_GET['gameId'] ?? 0;

    $query = "
        SELECT
            g.*, 
            p.publisherName, 
            d.developerName,
            ag.detailedDesc, ag.videoUrl, ag.imageUrl,
            (SELECT GROUP_CONCAT(categoryName) 
        FROM GameCategory gc 
        JOIN Category c ON gc.categoryId = c.categoryId 
        WHERE gc.gameId = g.gameId) AS categories
        FROM Game g
        JOIN Publisher p ON g.publisherId = p.publisherId
        JOIN Developer d ON g.developerId = d.developerId
        LEFT JOIN AboutGame ag ON g.gameId = ag.gameId
        WHERE g.gameId = ?
        ";
    $statement = $connect->prepare($query);
    $statement->bind_param("i", $gameId);
    $statement->execute();
    $result = $statement->get_result();
    $game = $result->fetch_assoc();

    if (!$game) {
        echo "Game not found.";
        exit;
    } else {
        header("Content-Type: application/json");
        echo json_encode($games);
    }
?>