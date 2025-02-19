<?php
require 'db.php';

$data = json_decode(file_get_contents("php://input"));

if (!empty($data->email) && !empty($data->password)) {
    $email = htmlspecialchars(strip_tags($data->email));
    $password = $data->password;

    $query = "SELECT id, password, role,  first_name, last_name, avatar_url, profile_bio, created_at FROM users WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $email, $hashed_password);
        $stmt->fetch();

        if (password_verify($password, $hashed_password)) {
            $token = bin2hex(random_bytes(32)); // Generate a random token
            echo json_encode([
                "success" => true,
                "message" => "Login successful.",
                "user" => ["id" => $id, "email" => $email, "token" => $token]
            ]);
        } else {
            echo json_encode(["success" => false, "message" => "Incorrect password."]);
        }
    } else {
        echo json_encode(["success" => false, "message" => "User not found."]);
    }
} else {
    echo json_encode(["success" => false, "message" => "All fields are required."]);
}
?>
