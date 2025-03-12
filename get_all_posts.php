<?php
require 'db.php';

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: GET");

// Fetch all posts from the database
$query = "SELECT 
            p.id, 
            p.content, 
            p.created_at, 
            u.first_name, 
            u.last_name, 
            u.avatar_url,
            c.community_name
          FROM posts p
          JOIN users u ON p.user_id = u.id
          JOIN communities c ON p.community_id = c.id
          ORDER BY p.created_at DESC";

$result = $conn->query($query);

if ($result->num_rows > 0) {
    $posts = [];
    while ($row = $result->fetch_assoc()) {
        $posts[] = $row;
    }
    echo json_encode(["success" => true, "posts" => $posts]);
} else {
    echo json_encode(["success" => false, "message" => "No posts found."]);
}

$conn->close();
?>
