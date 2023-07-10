<?php

require_once "config.php";
require_once "functions.php";
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    if ($_SERVER['REQUEST_URI'] == '/get-plans.php/sub') {
        if (!isset($_POST['id'])) {
            $respond = [
                "status" => -1,
                "message" => "please send all parameters (id)"
            ];
            die(json_encode($respond));
        }
        $id = $_POST['id'];
        if($subplan = Select_stmt($conn, "SELECT `name`,`subject_id`,`plan` FROM `plans` WHERE `id` = ?", "i", $id)){
            $subplan = $subplan[0];
            $subject = Select_stmt($conn, "SELECT `name` FROM `subjects` WHERE id = ?", "i", $subplan['subject_id'])[0];
            $subplan['subject'] = $subject['name'];
            unset($subplan['subject_id']);
        }

        $respond = [
            "status" => 1,
            "sub_plan" => $subplan
        ];
        die(json_encode($respond));
    } else if (!isset($_POST['username'])) {
        $respond = [
            "status" => -1,
            "message" => "please send all parameters (username)"
        ];
        die(json_encode($respond));
    }
    $username = $_POST['username'];
    if(!$user = Select_stmt($conn, "SELECT `id` FROM `users` WHERE `username` = ?", "s", $username)){
        $respond = [
            "status" => -2,
            "message" => "user not found"
        ];
        die(json_encode($respond));
    }
    $user = Select_stmt($conn, "SELECT `id` FROM `users` WHERE `username` = ?", "s", $username)[0];
    $plans = Select_stmt($conn, "SELECT `id`,`name`,`subject_id`,`level` FROM `plans` WHERE `user_id` = ?", "i", $user['id']);
    foreach ($plans as $key => $value) {
        $subject = Select_stmt($conn, "SELECT `name` FROM `subjects` WHERE `id` = ?", "i", $value['subject_id'])[0];
        unset($value['subject_id']);
        $value['subject'] = $subject['name'];
        unset($plans[$key]);
        $plans[$key] = $value;
    }

    // var_dump($plans);
    $respond = [
        "status" => 1,
        "plans" => $plans
    ];
    echo json_encode($respond);
}