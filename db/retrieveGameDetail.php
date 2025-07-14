<?php
    function getGameDetail($connect, $gameId) {
        $query = "
            SELECT g.*, 
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

        global $connect;
        $statement = $connect->prepare($query);
        $statement->bind_param("i", $gameId);
        $statement->execute();
        $result = $statement->get_result();
        return $result->fetch_assoc();
    }
?>
