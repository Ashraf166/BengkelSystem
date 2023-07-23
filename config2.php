<?php
// DB Configuration
$servername = "localhost";
$db_username = "root";
$db_password = "";
$database = "workshopDB";

// Connection to DB
$conn = mysqli_connect($servername, $db_username, $db_password, $database);

if (!$conn) {
    die("Connection Failed: " . mysqli_connect_error());
}
?>
