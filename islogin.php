<?php
require_once "config.php";
require_once "functions.php";

if ($_SERVER['REQUEST_METHOD'] == "POST" && $info = json_decode(file_get_contents("php://input"), true)) {
    $token = $info['token'];
    $userInfo = json_decode(encrypt_decrypt('decrypt', $token, 'bozi'), true);
    if(!isset($info['token'])) respond(-1, "please send token");
    if (!select_stmt($conn, "SELECT * FROM `users` WHERE username = ?", "s", $userInfo['username'])) respond(-2, "token wrong");
    respond(1, "user loged in");
}