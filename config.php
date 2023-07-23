<?php
// DB Configuration
$servername = "localhost";
$username = "root";
$password = "";
$database = "workshopDB";

// Connection to DB
$conn = mysqli_connect($servername, $username, $password, $database);

if (!$conn) {
    die("Connection Failed: " . mysqli_connect_error());
}
?>
