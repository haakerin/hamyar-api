<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, DELETE, PUT, PATCH, OPTIONS');
header('Access-Control-Allow-Headers: token, Content-Type');
header('Content-Type: application/json');
// error_reporting(0);
// Database configuration
$isonserver = false;
if ($isonserver) {
    $dbHost = "hamyar-db:3306";
    $dbUsername = "root";
    $dbPassword = "Z8Ej2Ky0P8zH1MedAVSTSzj8";
    $dbName = "hamyar";
} else {
    $dbHost = "localhost";
    $dbUsername = "root";
    $dbPassword = "";
    $dbName = "hamyar";
}


// Create database connection
$conn = mysqli_connect($dbHost, $dbUsername, $dbPassword, $dbName);

//check db connected
if (!$conn) {
    die("connection failed" . mysqli_connect_error());
}
