<?php
    function retrieveGameDetails($connect, $gameId) {
        $query = "
            SELECT *
            FROM Game g
            JOIN AboutGame ab ON g.gameId = ab.gameId
            JOIN Publisher p ON g.publisherId = p.publisherId
            WHERE g.gameId = ?
        ";

        $statement = $connect->prepare($query);
        $statement->bind_param("i", $gameId);
        $statement->execute();
        $result = $statement->get_result();
        return $result->fetch_assoc();
    }

    function retrieveSystemRequirements($connect, $gameId) {
        $systemReqs = [
            'Minimum' => null,
            'Recommended' => null
        ];

        $query = "
            SELECT sr.*
            FROM SystemRequirement sr
            WHERE sr.gameId = ?
        ";
        $statement = $connect->prepare($query);
        $statement->bind_param("i", $gameId);
        $statement->execute();
        $result = $statement->get_result();

        while ($row = $result->fetch_assoc()) {
            if (strtolower($row['type']) === 'minimum') {
                $systemReqs['Minimum'] = $row;
            } elseif (strtolower($row['type']) === 'recommended') {
                $systemReqs['Recommended'] = $row;
            }
        }

        return $systemReqs;
    }

    function retrieveGamePreviews($connect, $gameId) {
        $gamePreviews = [
            'video' => [],
            'image' => []
        ];

        $query = "
            SELECT gp.*
            FROM GamePreview gp
            WHERE gp.gameId = ?
        ";
        $statement = $connect->prepare($query);
        $statement->bind_param("i", $gameId);
        $statement->execute();
        $result = $statement->get_result();

        while ($row = $result->fetch_assoc()) {
            if (strtolower($row['type']) === 'video') {
                $gamePreviews['video'][] = $row;
            } elseif (strtolower($row['type']) === 'image') {
                $gamePreviews['image'][] = $row;
            }
        }

        return $gamePreviews;
    }

    function retrieveGameCategories($connect, $gameId) {
        $gameCategories = [
            'categories' => []
        ];

        $query = "
            SELECT c.categoryId, c.categoryName
            FROM GameCategory gc
            JOIN Category c ON gc.categoryId = c.categoryId
            WHERE gc.gameId = ?
        ";

        $statement = $connect->prepare($query);
        $statement->bind_param("i", $gameId);
        $statement->execute();
        $result = $statement->get_result();

        while ($row = $result->fetch_assoc()) {
            $gameCategories['categories'][] = $row;
        }

        return $gameCategories;
    }
?>
