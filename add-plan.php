<?php
require_once "./config.php";
require_once "./functions.php";

header('Content-Type: application/json');
if ($_SERVER['REQUEST_METHOD'] == "POST" && $info = json_decode(file_get_contents("php://input"), true)) {

    if (!isset($info['username'], $info['subject'], $info['plan_name'], $info['user_times'])) {
        $respond = [
            "status" => -1,
            "message" => "please send all parameters (username, subject, plan_name, user_times)"
        ];
        die(json_encode($respond));
    }
    $username = input_sec($info['username']);
    $subject = input_sec($info['subject']);
    $plan_name = input_sec($info['plan_name']);
    $user_times = str_replace("&quot;", "\"", input_sec($info['user_times']));

    if (add_plan_validation($user_times) === 1) {
        $respond = [
            "status" => -2,
            "message" => "زمان وارد شده از حد (100 ساعت) بیشتر است"
        ];
        die(json_encode($respond));
    } else if (add_plan_validation($user_times) === -1) {
        $respond = [
            "status" => -2,
            "message" => "زمان وارد شده از حد نصاب (۲۴ ساعت) کمتر است"
        ];
        die(json_encode($respond));
    }
    $user = select_stmt($conn, "SELECT id FROM users WHERE username = ?", "s", $username)[0];
    $subject = select_stmt($conn, "SELECT id,grade FROM subjects WHERE name = ?", "s", $subject)[0];

    $level = level($subject['grade'], $user_times);
    $plan = plan($user_times);

    if (insert_stmt($conn, "INSERT INTO plans(user_id, subject_id, name, user_times,level,plan) VALUES(?,?,?,?,?,?)", "iissis", $user['id'], $subject['id'], $plan_name, $user_times, $level, $plan)) {
        $respond = [
            "status" => 1,
            "message" => "add plan is successfull"
        ];
        echo json_encode($respond);
    } else {
        $respond = [
            "status" => 0,
            "message" => "خطای ناگهانی در عملیات"
        ];
        echo json_encode($respond);
    }
}
