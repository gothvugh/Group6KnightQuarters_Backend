<?php
require 'db.php';

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data["email"], $data["password"])) {
    echo json_encode(["success" => false, "message" => "Email and password are required."]);
    exit;
}

$email = htmlspecialchars(strip_tags($data["email"]));
$password = $data["password"];

// Check if user exists
$query = "SELECT id, password FROM users WHERE email = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows === 0) {
    echo json_encode(["success" => false, "message" => "Invalid email or password."]);
    exit;
}

$stmt->bind_result($user_id, $hashed_password);
$stmt->fetch();

if (!password_verify($password, $hashed_password)) {
    echo json_encode(["success" => false, "message" => "Invalid email or password."]);
    exit;
}

// Login successful
echo json_encode(["success" => true, "message" => "Login successful!", "user_id" => $user_id]);

$stmt->close();
$conn->close();
?>
