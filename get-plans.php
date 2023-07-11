<?php
require_once "config.php";
require_once "functions.php";

if ($_SERVER['REQUEST_METHOD'] == "POST" && $info = json_decode(file_get_contents("php://input"), true)) {
    if ($_SERVER['REQUEST_URI'] == '/get-plans.php/sub') {
        if (!isset($info['id'])) respond(-1, "please send all parameters (id)");
        $id = $info['id'];
        if (!$subplan = select_subplan($id)) respond(-2, "not exist");
        $respond = [
            "status" => 1,
            "sub_plan" => $subplan
        ];
        die(json_encode($respond));
    } else if (!isset($info['username'])) {
        respond(-1, "please send all parameters (username)");
    }
    $username = $info['username'];
    if (!$user = selectUser($username)) respond(-2, "user not found");
    
    $plans = selectUserPlans($user['id']);
    
    // select_stmt($conn, "SELECT `id`,`name`,`subject_id`,`level` FROM `plans` WHERE `user_id` = ?", "i", $user['id']);
    // foreach ($plans as $key => $value) {
    //     $subject = select_stmt($conn, "SELECT `name` FROM `subjects` WHERE `id` = ?", "i", $value['subject_id'])[0];
    //     unset($value['subject_id']);
    //     $value['subject'] = $subject['name'];
    //     unset($plans[$key]);
    //     $plans[$key] = $value;
    // }
    respond(1,$plans);
}