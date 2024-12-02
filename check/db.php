<?php
$host = 'localhost'; // Hostname
$user = 'root';      // MySQL username
$password = '';      // MySQL password (usually empty for XAMPP)
$dbname = 'loginsystem'; // Database name

// Create a connection to the database
$con = mysqli_connect($host, $user, $password, $dbname);

// Check if the connection was successful
if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}
?>