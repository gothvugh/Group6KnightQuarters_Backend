<?php
require 'db.php';

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data["user_id"], $data["content"], $data["community_id"])) {
    echo json_encode(["success" => false, "message" => "All fields are required."]);
    exit;
}

$user_id = intval($data["user_id"]);
$content = htmlspecialchars(strip_tags($data["content"]));
$community_id = intval($data["community_id"]);

// Insert post into database
$query = "INSERT INTO posts (user_id, content, community_id, created_at) VALUES (?, ?, ?, NOW())";
$stmt = $conn->prepare($query);
$stmt->bind_param("isi", $user_id, $content, $community_id);

if ($stmt->execute()) {
    echo json_encode(["success" => true, "message" => "Post created successfully."]);
} else {
    echo json_encode(["success" => false, "message" => "Failed to create post."]);
}

$stmt->close();
$conn->close();
?>
