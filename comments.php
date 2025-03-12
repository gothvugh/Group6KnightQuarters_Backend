<?php
require 'db.php';

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: GET, POST");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

$method = $_SERVER['REQUEST_METHOD'];

$raw_input = file_get_contents("php://input");
file_put_contents("debug_log.txt", $raw_input); // Log raw JSON input

if ($method === 'POST') {

    // Getting a comment from the frontend
    $data = json_decode(file_get_contents("php://input"), true);

    if (!isset($data["user_id"], $data["post_id"], $data["content"]) || empty(trim($data["content"]))) {
        echo json_encode(["success" => false, "message" => "All fields are required."]);
        exit;
    }

    $user_id = intval($data["user_id"]);
    $post_id = intval($data["post_id"]);
    $content = htmlspecialchars(strip_tags($data["content"]));

    // Insert comment into the database
    $query = "INSERT INTO comments (creator_id, post_id, content, created_at) VALUES (?, ?, ?, NOW())";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("iis", $user_id, $post_id, $content);

    if ($stmt->execute()) {
        echo json_encode(["success" => true, "message" => "Comment added successfully."]);
    } else {
        echo json_encode(["success" => false, "message" => "Failed to add comment.", "error" => $conn->error]);
    }

    $stmt->close();
}

// Handle fetching comments
elseif ($method === 'GET') {
    if (!isset($_GET['post_id']) || empty($_GET['post_id'])) {
        echo json_encode(["success" => false, "message" => "Post ID is required."]);
        exit;
    }

    $post_id = intval($_GET['post_id']);

    $sql = "SELECT 
                c.id AS comment_id, 
                c.content, 
                c.created_at, 
                u.id AS user_id, 
                u.first_name, 
                u.last_name, 
                u.avatar_url 
            FROM comments c
            JOIN users u ON c.creator_id = u.id
            WHERE c.post_id = ?
            ORDER BY c.created_at ASC";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $post_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $comments = [];
    while ($row = $result->fetch_assoc()) {
        $comments[] = $row;
    }

    if (count($comments) > 0) {
        echo json_encode(["success" => true, "comments" => $comments]);
    } else {
        echo json_encode(["success" => false, "message" => "No comments found."]);
    }

    $stmt->close();
}

$conn->close();
?>
