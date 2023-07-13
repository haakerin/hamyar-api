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
    } else
        respond(-1, "Please send both email and password.");
    $user = select_stmt($conn, "SELECT * FROM `users` where `username` = ? or `email` = ?", "ss", $username, $username)[0];
    if ($user && password_verify($password, $user['password'])) {
        $user_info = json_encode(["username" => $user['username'], "email" => $user['email'], "name" => $user['name']]);
        $respond = [
            "status" => 1,
            "message" => "user loged in",
            "user" => [
                "username" => $user['username'],
                "email" => $user['email']
            ],
            "token" => generate_token($user_info)
        ];
        echo json_encode($respond);
    } else {
        $respond = [
            "status" => -2,
            "message" => "نام کاربری یا رمز عبور اشتباه است"
        ];
        mysqli_close($conn);
        echo json_encode($respond);
    }
}
