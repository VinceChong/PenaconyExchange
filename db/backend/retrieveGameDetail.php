<?php
function retrieveGameDetail($connect, $gameId) {
    $query = "
        SELECT *
        FROM Game g
        JOIN AboutGame ab ON g.gameId = ab.gameId
        JOIN GamePreview gp ON g.gameId = gp.gameId
        JOIN GameCategory gc ON g.gameId = gc.gameId
        JOIN Category c ON gc.categoryId = c.categoryId
        JOIN Publisher p ON g.publisherId = p.publisherId
        WHERE g.gameId = ?
    ";

    $statement = $connect->prepare($query);
    $statement->bind_param("i", $gameId);
    $statement->execute();
    $result = $statement->get_result();
    return $result->fetch_assoc();
}

function retrieveSystemRequirement($connect, $gameId) {
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
?>
