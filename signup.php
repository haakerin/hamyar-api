<?php
require_once "./config.php";
require_once "./functions.php";

// Retrieve POST data
if ($_SERVER['REQUEST_METHOD'] == "POST" && $info = json_decode(file_get_contents("php://input"), true)) {
    if (!isset($info['username'], $info['email'], $info['password'], $info['name'])) {
        respond(-1, "please send all parameters (username, email, password, name)");
    }
    $username = input_sec($info['username']);
    $name = input_sec($info['name']);
    $email = input_sec($info['email']);
    $password = input_sec($info['password']);

    // Hash the password for security
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    if (!signup_validation($username, $email, $password))
        respond(-3, "validation error");
    if (select_stmt($conn, "SELECT * FROM `users` WHERE `username` = ?", "s", $username))
        respond(-2, "کاربری با این مشخصات وجود دارد");
    if (stmt($conn, "INSERT INTO users (`username`,`name`,email,`password`) VALUES (?,?,?,?)", "ssss", $username, $name, $email, $hashedPassword))
        $respond = [
            "status" => 1,
            "message" => "Sign-up successful."
        ];
    else
        $respond = [
            "status" => 0,
            "message" => "خطا در ثبت نام"
        ];
    // Close the statement and database connection
    mysqli_close($conn);
    // Return the JSON response
    echo json_encode($respond);
}
