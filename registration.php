<?php
header("Content-Type: application/json");
require "db_connect.php"; // Database connection

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $data = json_decode(file_get_contents("php://input"), true);

    if (isset($data['username']) && isset($data['password'])) {
        $username = trim($data['username']);
        $password = $data['password'];

        if (empty($username) || empty($password)) {
            echo json_encode(["status" => "error", "message" => "Username and password are required"]);
            exit;
        }

        try {
            $pdo = connectDB();
            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

            $stmt = $pdo->prepare("INSERT INTO users (username, password_hash) VALUES (:username, :password)");
            $stmt->execute(["username" => $username, "password" => $hashedPassword]);

            echo json_encode(["status" => "success", "message" => "User registered successfully"]);
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) { // Unique constraint violation
                echo json_encode(["status" => "error", "message" => "Username already exists"]);
            } else {
                echo json_encode(["status" => "error", "message" => "Database error"]);
            }
        }
    } else {
        echo json_encode(["status" => "error", "message" => "Invalid input"]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Invalid request method"]);
}
?>
