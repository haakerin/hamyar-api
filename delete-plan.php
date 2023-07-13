<?php
require_once "./config.php";
require_once "./functions.php";
if ($_SERVER['REQUEST_METHOD'] == "POST" && $info = json_decode(file_get_contents("php://input"), true)) {
    if(!isset($info['id'], $info['token'])) respond(-1, "please send all parameters (token and id)");
    $plan_id = $info['id'];
    $token = $info['token'];
    $userInfo = json_decode(encrypt_decrypt('decrypt', $token, 'bozi'), true);
    if(!select_stmt($conn, "SELECT * FROM `users` WHERE username = ?", "s", $userInfo['username'])) respond(-5, "token wrong");
    if(stmt($conn, "DELETE FROM `plans` WHERE `id` = ?", "i", $plan_id))
        respond(1, "delete succefull");
    else respond(0, "خطای ناگهانی");
    mysqli_close($conn);
}