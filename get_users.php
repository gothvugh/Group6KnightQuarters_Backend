<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

include "db.php";

$sql = "SELECT id, email, password, role,  first_name, last_name, avatar_url, profile_bio, communities_followed, communities_created, posts_created, comments_created, messages, created_at FROM users";
$result = $conn->query($sql);

$users = [];
while ($row = $result->fetch_assoc()) {
    $users[] = $row;
}

echo json_encode($users);
?>

