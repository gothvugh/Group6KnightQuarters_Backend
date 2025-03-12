<?php
require 'db.php';
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

include "db.php";

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['sender_id'], $data['receiver_id'], $data['content'])) {
    echo json_encode(["error" => "Missing required fields"]);
    exit;
}

$sender_id = $data['sender_id'];
$receiver_id = $data['receiver_id'];
$content = $conn->real_escape_string($data['content']);

$sql = "INSERT INTO messages (sender_id, receiver_id, content, created_at) VALUES (?, ?, ?, NOW())";

$stmt = $conn->prepare($sql);
$stmt->bind_param("iis", $sender_id, $receiver_id, $content);

if ($stmt->execute()) {
    echo json_encode(["success" => true, "message_id" => $stmt->insert_id]);
} else {
    echo json_encode(["error" => "Message not sent"]);
}
?>
