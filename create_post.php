<?php
require 'db.php';

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

// Read and store raw input
$raw_input = file_get_contents("php://input");
file_put_contents("debug_log.txt", $raw_input); // Log raw JSON input

// Decode JSON
$data = json_decode($raw_input, true);

// Validate Required Fields
if (!isset($data["user_id"], $data["content"], $data["community_id"])) {
    echo json_encode(["success" => false, "message" => "All fields are required.", "received" => $data]);
    exit;
}

// Assign Data
$user_id = intval($data["user_id"]);
$content = trim($data["content"]);
$community_id = intval($data["community_id"]);

// Ensure Values Are Not Empty
if ($user_id === 0 || empty($content) || $community_id === 0) {
    echo json_encode(["success" => false, "message" => "Invalid or missing values.", "user_id" => $user_id, "content" => $content, "community_id" => $community_id]);
    exit;
}

// Insert Post into Database
$query = "INSERT INTO posts (user_id, content, community_id, created_at) VALUES (?, ?, ?, NOW())";
$stmt = $conn->prepare($query);
$stmt->bind_param("isi", $user_id, $content, $community_id);

if ($stmt->execute()) {
    echo json_encode(["success" => true, "message" => "Post created successfully."]);
} else {
    echo json_encode(["success" => false, "message" => "Database insert failed.", "error" => $conn->error]);
}

$stmt->close();
$conn->close();
?>
