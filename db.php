<?php
$host = "localhost"; // Change if using a remote server
$username = "root"; // Change to your MySQL username
$password = ""; // Change to your MySQL password
$database = "footcap"; // Your database name

// Create connection
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
