<?php
// includes/db.php

$host = "localhost";
$username = "root";
$password = "";
$database = "education_site";

// Create DB connection
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
