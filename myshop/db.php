
<?php

$host = "localhost";
$user = "root";
$password = "";
$database = "myshop";

$conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {

    die("DATABASE LOI: " . $conn->connect_error);

}

$conn->set_charset("utf8mb4");

?>