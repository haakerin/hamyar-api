<?php
require_once "functions.php";
require_once "config.php";
header('Content-Type: application/json');
// Retrieve POST data
if ((isset($_POST['username']) xor isset($_POST['email'])) && isset($_POST['password'])) {
    if (isset($_POST['username'])) $username = input_sec($_POST['username']);
    else if (isset($_POST['email'])) $username = input_sec($_POST['email']);
    $password = input_sec($_POST['password']);
    // Hash the password for security
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
}else {
    $respond = [
        "status" => "error",
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
        "status" => "success",
        "message" => "user loged in",
        "user" => [
            "username" => $fetched_username,
            "email" => $fetched_email
        ]
    ];
    echo json_encode($respond);
} else {
    $respond = [
        "status" => "error",
        "message" => "username or pasword is incorrect!"
    ];

    echo json_encode($respond);
}
