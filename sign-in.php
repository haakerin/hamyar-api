<?php
require_once "functions.php";
require_once "config.php";

// Retrieve POST data
if ($_SERVER['REQUEST_METHOD'] == "POST" && $info = json_decode(file_get_contents("php://input"), true)) {
    if ((isset($info['username']) xor isset($info['email'])) && isset($info['password'])) {
        $username = isset($info['username']) ? input_sec($info['username']) : (isset($info['email']) ? input_sec($info['email']) : null);
        $password = input_sec($info['password']);

        // Hash the password for security
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    } else {
        $respond = [
            "status" => -1,
            "message" => "Please send both email and password."
        ];
        die(json_encode($respond));
    }
    $result = select_stmt($conn, "SELECT * FROM users where username = ? or email = ?", "ss", $username, $username);
    if ($result && password_verify($password, $fetched_password)) {
        $user = $result[0];
        $respond = [
            "status" => 1,
            "message" => "user loged in",
            "user" => [
                "username" => $user['username'],
                "email" => $user['email']
            ]
        ];
        echo json_encode($respond);
    } else {
        $respond = [
            "status" => -2,
            "message" => "نام کاربری یا رمز عبور اشتباه است"
        ];

        echo json_encode($respond);
    }
}
