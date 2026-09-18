<?php

$host = "localhost";
$user = "nguyenkimhau1203";
$password = "hau123";
$database = "myshop";

$conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

$conn->set_charset("utf8mb4");

?>