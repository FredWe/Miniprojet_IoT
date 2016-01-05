<?php
$servername = "localhost";
$username = "pi";
$password = "raspberry";

// Connection established
$conn = new mysqli($servername, $username, $password);

// Connection detected
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 
echo "Connected successfully";

$conn->close();
?>

