<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, DELETE, PUT, PATCH, OPTIONS');
header('Access-Control-Allow-Headers: token, Content-Type');
header('Content-Type: application/json');
// Database configuration
$dbHost = "aberama.iran.liara.ir:31984";
$dbUsername = "root";
$dbPassword = "Z8Ej2Ky0P8zH1MedAVSTSzj8";
$dbName = "hamyar";

// Create database connection
$conn = mysqli_connect($dbHost, $dbUsername,$dbPassword, $dbName);

//check db connected
if (mysqli_connect_errno()) {
    die("connection failed" . mysqli_connect_error());
}
?>