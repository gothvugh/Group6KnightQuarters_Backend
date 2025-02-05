
// This is a template for your db.php file
<?php
$host = "your_host";  // Example: "localhost"
$user = "your_username";  // Example: "root"
$password = "your_password";  // Example: ""
$database = "your_database_name";  // Example: "knightquarters"

$conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
    die(json_encode(["error" => "Database connection failed: " . $conn->connect_error]));
}
?>
