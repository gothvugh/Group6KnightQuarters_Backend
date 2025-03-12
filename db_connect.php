<?php
$host = 'localhost';
$user = 'root';
$password = 'root';
$database = 'knightquarters'; 

$port = 8888;
$socket = '/Applications/MAMP/tmp/mysql/mysql.sock';

$conn = new mysqli($host, $user, $password, $database, $port, $socket);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
