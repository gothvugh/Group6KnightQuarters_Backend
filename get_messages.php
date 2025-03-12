<?php
require 'db.php';
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

include "db.php";

$sql = "SELECT m.id, m.sender_id, m.receiver_id, m.content, m.created_at, 
        u1.first_name AS sender_name, u1.avatar_url AS sender_avatar
        FROM messages m
        JOIN users u1 ON m.sender_id = u1.id
        ORDER BY m.created_at DESC";

$result = $conn->query($sql);
$messages = [];

while ($row = $result->fetch_assoc()) {
    $messages[] = $row;
}

echo json_encode($messages);
?>
