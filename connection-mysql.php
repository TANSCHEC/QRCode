<?php
// MySQL DB connection
$host = 'localhost';        // Change as needed
$username = 'tans';         // MySQL username
$password = 'tans';         // MySQL password
$database = 'tans';         // MySQL database name

$conn = mysqli_connect($host, $username, $password, $database);
mysqli_set_charset($conn, 'utf8mb4'); 

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>