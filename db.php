<?php
$host = "sql101.byethost7.com";
$username = "b7_39023074";
$password = "Hy.srT_ZUG\$Ygv6";
$database = "b7_39023074_Spookies_Users";

// Create connection
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
