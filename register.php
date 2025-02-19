<?php
require 'db.php';

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data["email"], $data["password"], $data["first_name"], $data["last_name"])) {
    echo json_encode(["success" => false, "message" => "All fields are required."]);
    exit;
}

$email = htmlspecialchars(strip_tags($data["email"]));
$password = password_hash($data["password"], PASSWORD_DEFAULT);
$first_name = htmlspecialchars(strip_tags($data["first_name"]));
$last_name = htmlspecialchars(strip_tags($data["last_name"]));

// Prevent duplicate emails
$checkQuery = "SELECT id FROM users WHERE email = ?";
$checkStmt = $conn->prepare($checkQuery);
$checkStmt->bind_param("s", $email);
$checkStmt->execute();
$checkStmt->store_result();

if ($checkStmt->num_rows > 0) {
    echo json_encode(["success" => false, "message" => "Email already exists."]);
    exit;
}

// Insert new user
$query = "INSERT INTO users (email, password, first_name, last_name) VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($query);
$stmt->bind_param("ssss", $email, $password, $first_name, $last_name);

if ($stmt->execute()) {
    echo json_encode(["success" => true, "message" => "User registered successfully."]);
} else {
    echo json_encode(["success" => false, "message" => "Registration failed."]);
}

$stmt->close();
$checkStmt->close();
$conn->close();
?>
