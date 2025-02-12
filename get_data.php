<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

include "db.php";

$sql = "
    SELECT users.id, users.first_name, users.last_name, users.email, 
           posts.id AS post_id, posts.content, posts.created_at
    FROM users
    LEFT JOIN posts ON users.id = posts.user_id
";
$result = $conn->query($sql);

$data = [];
while ($row = $result->fetch_assoc()) {
    $userId = $row['id'];

    if (!isset($data[$userId])) {
        $data[$userId] = [
            "id" => $row["id"],
            "first_name" => $row["first_name"],
            "last_name" => $row["last_name"],
            "email" => $row["email"],
            "posts" => []
        ];
    }

    if (!empty($row["post_id"])) {
        $data[$userId]["posts"][] = [
            "post_id" => $row["post_id"],
            "content" => $row["content"],
            "created_at" => $row["created_at"]
        ];
    }
}

echo json_encode(array_values($data));
?>
