<?php
// Database configuration
$dbHost = "hamyar-db:3306";
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