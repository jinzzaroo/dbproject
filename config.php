<?php
$servername = "192.168.56.101";
$port = "4567";
$username = "jykim";
$password = "2028576Kjy!";
$database = "bs";

$conn = new mysqli($servername, $username, $password, $database, $port);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
