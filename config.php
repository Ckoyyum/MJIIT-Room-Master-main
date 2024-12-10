<?php
$servername = "localhost";
$username = "root";
$dbpassword = "";  // Your MySQL password
$dbname = "mjiitroommasterdb";

// Creating connection
$conn = new mysqli('localhost', 'root', '', 'mjiitroommasterdb');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

?>

