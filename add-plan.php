<?php
require_once "./config.php";
require_once "./functions.php";

header('Content-Type: application/json');
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    if (!isset($_POST['username'], $_POST['subject'], $_POST['plan_name'], $_POST['user_times'])) {
        $respond = [
            "status" => -1,
            "message" => "please send all parameters (username, subject, plan_name, user_times)"
        ];
        die(json_encode($respond));
    }

    $username = input_sec($_POST['username']);
    $subject = input_sec($_POST['subject']);
    $plan_name = input_sec($_POST['plan_name']);
    $user_times = str_replace("&quot;","\"", input_sec($_POST['user_times']));

	if(add_plan_validation($user_times) === 1) {
		$respond = [
			"status" => -2,
			"message" => "times is very much"
		];
		die(json_encode($respond));
	} else if(add_plan_validation($user_times) === -1){
		$respond = [
			"status" => -2,
			"message" => "times is very low"
		];
		die(json_encode($respond));
	}
    $user = Select_stmt($conn, "SELECT id FROM users WHERE username = ?", "s", $username)[0];
    $subject = Select_stmt($conn, "SELECT id,grade FROM subjects WHERE name = ?", "s", $subject)[0];
    
    $level = Level($subject['grade'], $user_times);
    $plan = Plan($user_times);

    if(Insert_stmt($conn, "INSERT INTO plans(user_id, subject_id, name, user_times,level,plan) VALUES(?,?,?,?,?,?)", "iissis", $user['id'], $subject['id'], $plan_name, $user_times, $level,$plan)){
        $respond = [
            "status" => 1,
            "message" => "add plan is successfull"
        ];
        echo json_encode($respond);
    }else{
        $respond = [
            "status" => 0,
            "message" => "error in add plan"
        ];
        echo json_encode($respond);
    }
}
