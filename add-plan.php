<?php
require_once "./config.php";
require_once "./functions.php";

if ($_SERVER['REQUEST_METHOD'] == "POST" && $info = json_decode(file_get_contents("php://input"), true)) {
    $info['user_times'] = json_encode($info['user_times']);
    if (!isset($info['username'], $info['subject'], $info['plan_name'], $info['user_times']))
        respond(-1, "please send all parameters (username, subject, plan_name, user_times)");
    $username = input_sec($info['username']);
    $subject = input_sec($info['subject']);
    $plan_name = input_sec($info['plan_name']);
    $user_times = str_replace("&quot;", "\"", input_sec($info['user_times']));

    if (add_plan_validation($user_times) === 1)
        respond(-2, "زمان وارد شده از حد (100 ساعت) بیشتر است");
    else if (add_plan_validation($user_times) === -1)
        respond(-2, "زمان وارد شده از حد نصاب (۲۴ ساعت) کمتر است");

    $user = select_stmt($conn, "SELECT id FROM users WHERE username =?", "s", $username);
    if (!$user)
        respond(-3, "username not exist");

    $user = $user[0];
    $subject = select_stmt($conn, "SELECT id,grade FROM subjects WHERE name = ?", "s", $subject);
    if (!$subject)
        respond(-3, "subject not exist");

    $subject = $subject[0];
    $level = level($subject['grade'], $user_times);
    $plan = plan($user_times);

    if (stmt($conn, "INSERT INTO plans(user_id, subject_id, name, user_times,level,plan) VALUES(?,?,?,?,?,?)", "iissis", $user['id'], $subject['id'], $plan_name, $user_times, $level, $plan))
        respond(1, "add plan is successfull");
    $respond = [
        "status" => 0,
        "message" => "خطای ناگهانی در عملیات"
    ];

    mysqli_close($conn);
    echo json_encode($respond);
}
