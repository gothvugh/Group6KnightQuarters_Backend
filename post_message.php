<?php
require 'db.php';

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data["sender_id"], $data["receiver_id"], $data["content"])) {
    echo json_encode(["success" => false, "message" => "All fields are required."]);
    exit;
}

$sender_id = htmlspecialchars(strip_tags($data["sender_id"]));
$receiver_id = htmlspecialchars(strip_tags($data["receiver_id"]));
$content = htmlspecialchars(strip_tags($data["content"]));


// Insert new user
$query = "INSERT INTO messages (sender_id, receiver_id, content) VALUES (?, ?, ?)";
$stmt = $conn->prepare($query);
$stmt->bind_param("sss", $sender_id, $receiver_id, $content);

if ($stmt->execute()) {
    echo json_encode(["success" => true, "message" => "message registered successfully."]);
} else {
    echo json_encode(["success" => false, "message" => "message Registration failed."]);
}

$stmt->close();
$conn->close();
?>
