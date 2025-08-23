<?php
// include your mysqli connection
require_once __DIR__ . '/../db/backend/db.php'; 

session_start();
$gameId      = isset($_POST['gameId']) ? (int)$_POST['gameId'] : 0;
$rating      = isset($_POST['rating']) ? (int)$_POST['rating'] : 0;
$commentDesc = isset($_POST['commentDesc']) ? trim($_POST['commentDesc']) : '';
$userId      = $_SESSION['userId'] ?? 0; // or null if you allow anonymous

if ($gameId <= 0 || $rating < 1 || $rating > 5 || $commentDesc === '') {
    http_response_code(400);
    exit('Invalid review');
}

$sql = "INSERT INTO comment (userId, gameId, rating, commentDesc) VALUES (?, ?, ?, ?)";
$stmt = $connect->prepare($sql);
if (!$stmt) {
    http_response_code(500);
    exit('Prepare failed: ' . $connect->error);
}
$stmt->bind_param('iiis', $userId, $gameId, $rating, $commentDesc);

if (!$stmt->execute()) {
    http_response_code(500);
    exit('Execute failed: ' . $stmt->error);
}
$stmt->close();

// redirect back to the game page
header('Location: /PenaconyExchange/pages/purchasedGame.php?gameId=' . urlencode($gameId) . '&review=success');
exit;
