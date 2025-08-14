<?php
    session_start();
    include "../db.php";

    if (!isset($_SESSION["publisher"]["publisherId"])) {
        http_response_code(401);
        echo json_encode(["error" => "Unauthorized"]);
        exit;
    }

    $publisherId = $_SESSION["publisher"]["publisherId"];

    $query = "SELECT * FROM Game WHERE publisherId = ?";
    $statement = $connect->prepare($query);
    $statement->bind_param("i", $publisherId);
    $statement->execute();
    $result = $statement->get_result();

    $games = [];
    while ($row = $result->fetch_assoc()) {
        $games[] = $row;
    }

    header("Content-Type: application/json");
    echo json_encode($games, JSON_UNESCAPED_UNICODE);
?>
