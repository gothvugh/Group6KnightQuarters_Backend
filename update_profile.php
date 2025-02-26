<?php
require 'db.php';

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

$data = json_decode(file_get_contents("php://input"), true);

/*if (!isset($data["user_id"], $data["avatar_url"], $data["profile_bio"], $data["major"])) {
    echo json_encode(["success" => false, "message" => "All fields are required."]);
    exit;
}*/

$user_id = intval($data["user_id"]);
$avatar_url = htmlspecialchars(strip_tags($data["avatar_url"]));
$profile_bio = htmlspecialchars(strip_tags($data["profile_bio"]));
$major = htmlspecialchars(strip_tags($data["major"]));

$query = "UPDATE users SET avatar_url = ?, profile_bio = ?, major = ? WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("sssi", $avatar_url, $profile_bio, $major, $user_id);

if ($stmt->execute()) {
    echo json_encode(["success" => true, "message" => "Profile updated successfully."]);
} else {
    echo json_encode(["success" => false, "message" => "Failed to update profile."]);
}

$stmt->close();
$conn->close();
?>
