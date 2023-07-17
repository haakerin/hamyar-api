<?php
require_once "config.php";
require_once "functions.php";

if ($_SERVER['REQUEST_METHOD'] == "POST" && $info = json_decode(file_get_contents("php://input"), true)) {
    if (!isset($info['token'])) respond(-1, "please send token");
    $token = $info['token'];
    $userInfo = json_decode(encrypt_decrypt('decrypt', $token, 'bozi'), true);
    if (!select_stmt($conn, "SELECT * FROM `users` WHERE username = ?", "s", $userInfo['username'])) respond(-5, "token wrong");
    $respond = [
        "status" => 1,
        "user_info" => ["id" => $userInfo['id'],"name" => $userInfo['name'],"username" => $userInfo['username'],"email" => $userInfo['email']]
    ];
    
    mysqli_close($conn);
    echo json_encode($respond);
}
