<?php
require_once "config.php";
require_once "functions.php";

if ($_SERVER['REQUEST_METHOD'] == "POST" && $info = json_decode(file_get_contents("php://input"), true)) {
    if ($_SERVER['REQUEST_URI'] == '/get-plans.php/sub') {
        if (!isset($info['id'], $info['token'])) respond(-1, "please send all parameters (id)");
        $token = $info['token'];
        $id = $info['id'];
        if (!select_stmt($conn, "SELECT * FROM `users` WHERE username = ?", "s", $userInfo['username'])) respond(-5, "token wrong");
        if (!$subplan = select_subplan($id)) respond(-2, "not exist");
        $respond = [
            "status" => 1,
            "sub_plan" => $subplan
        ];
        die(json_encode($respond));
    } else if (!isset($info['token']))
        respond(-1, "please send all parameters (username)");
    $token = $info['token'];
    $userInfo = json_decode(encrypt_decrypt('decrypt', $token, 'bozi'), true);
    if (!select_stmt($conn, "SELECT * FROM `users` WHERE username = ?", "s", $userInfo['username'])) respond(-5, "token wrong");
    if (!$user = selectUser($userInfo['username'])) respond(-2, "user not found");

    $plans = selectUserPlans($user['id']);

    mysqli_close($conn);
    respond(1, $plans);
}
