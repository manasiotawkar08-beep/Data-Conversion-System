<?php

$host = "localhost";
$user = "root";
$password = "manasi@123";
$database = "converter_system";

$conn = mysqli_connect($host, $user, $password, $database);

if (!$conn) {
    die("Database Connection Failed: " . mysqli_connect_error());
}

?>