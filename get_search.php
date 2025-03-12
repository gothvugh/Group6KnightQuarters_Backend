<?php
require 'db.php';

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

// Search query
$query = isset($_GET['query']) ? trim($_GET['query']) : '';

if (empty($query)) {
    echo json_encode(["success" => false, "message" => "No search query provided."]);
    exit;
}

// Split query into keywords
$keywords = explode(" ", $query);
$searchConditions = [];

foreach ($keywords as $word) {
    $searchConditions[] = "p.content LIKE ?";
}

// Query to join users and posts
$sql = "SELECT 
            p.id, 
            p.content, 
            p.created_at, 
            u.id AS user_id, 
            u.first_name, 
            u.last_name,
            u.avatar_url,
            c.community_name
        FROM posts p
        JOIN users u ON p.user_id = u.id
        JOIN communities c ON p.community_id = c.id
        WHERE " . implode(" OR ", $searchConditions);

$stmt = $conn->prepare($sql);

// Bind parameters dynamically
$bindTypes = str_repeat("s", count($keywords));
$bindValues = array_map(fn($word) => "%$word%", $keywords);
$stmt->bind_param($bindTypes, ...$bindValues);

$stmt->execute();
$result = $stmt->get_result();

$posts = [];
while ($row = $result->fetch_assoc()) {
    $posts[] = $row;
}

// Return results only
if (count($posts) > 0) {
    echo json_encode(["success" => true, "posts" => $posts]);
} else {
    echo json_encode(["success" => false, "message" => "No matching posts found."]);
}

$stmt->close();
$conn->close();
?>
