<?php
require 'db.php';

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

// Fetch all communities
$query = "SELECT id, description, community_name AS name, creator_id, created_at FROM communities";
$result = $conn->query($query);

if ($result->num_rows > 0) {
    $communities = [];
    while ($row = $result->fetch_assoc()) {
        $communities[] = $row;
    }
    echo json_encode(["success" => true, "communities" => $communities]);
} else {
    echo json_encode(["success" => false, "message" => "No communities found."]);
}

$conn->close();
?>
