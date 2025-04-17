<?php
$host = "localhost:5222";
$user = "root";
$pass = "";
$dbname = "ngl_clone";

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
