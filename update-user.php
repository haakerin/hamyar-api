<?php
require_once "./config.php";
require_once "./functions.php";

// Retrieve POST data
if ($_SERVER['REQUEST_METHOD'] == "POST" && $info = json_decode(file_get_contents("php://input"), true)) {
    if (!isset($info['username'], $info['email'], $info['token'], $info['name']))
        respond(-1, "please send all parameters (token, username, email, name)");
    $token = $info['token'];
    $name = $info['name'];
    $username = $info['username'];
    $email = $info['email'];
    $userInfo = json_decode(encrypt_decrypt('decrypt', $token, 'bozi'), true);
    if (!select_stmt($conn, "SELECT * FROM `users` WHERE username = ?", "s", $userInfo['username'])) respond(-2, "token wrong");
    if (!update_user_validation($username, $email)) respond(-3, "validation error");
    if (select_stmt($conn, "SELECT * FROM `users` WHERE `username` = ?", "s", $username))
        respond(-2, "کاربری با این مشخصات وجود دارد");
    if (stmt($conn, "UPDATE `users` SET `username` = ?, `name` = ?, `email` = ? WHERE id = ?", "sssi", $username, $name, $email, $userInfo['id']))
        $respond = [
            "status" => 1,
            "message" => "successful"
        ];
    else
        $respond = [
            "status" => 0,
            "message" => "خطا در ویرایش"
        ];
}
