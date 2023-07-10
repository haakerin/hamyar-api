<?php
require_once "functions.php";
require_once "config.php";
header('Content-Type: application/json');
// Retrieve POST data
if (file_get_contents("php://input")) {
    $info = json_decode(file_get_contents("php://input"), true);
    $username = isset($info['username'])? input_sec($info['username']) : (isset($info['email'])? input_sec($info['email']) : null);
    $password = input_sec($info['password']);
}
if ((isset($info['username']) xor isset($info['email'])) && isset($info['password'])) {

    // Hash the password for security
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
}else {
    $respond = [
        "status" => -1,
        "message" => "please send (email or phone) and password"
    ];
    die(json_encode($respond));
}

$stmt = mysqli_prepare($conn, "SELECT * FROM users where username = ? or email = ?");
mysqli_stmt_bind_param($stmt, "ss", $username, $username);
mysqli_stmt_execute($stmt);
mysqli_stmt_store_result($stmt);
mysqli_stmt_bind_result($stmt, $fetched_id, $fetched_username, $fetched_email, $fetched_password);
mysqli_stmt_fetch($stmt);
if (mysqli_stmt_num_rows($stmt) > 0 && password_verify($password, $fetched_password)) {
    $respond = [
        "status" => 1,
        "message" => "user loged in",
        "user" => [
            "username" => $fetched_username,
            "email" => $fetched_email
        ]
    ];
    echo json_encode($respond);
} else {
    $respond = [
        "status" => -2,
        "message" => "username or pasword is incorrect!"
    ];

    echo json_encode($respond);
}
