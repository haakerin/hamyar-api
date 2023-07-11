<?php
require_once "./config.php";
require_once "./functions.php";
if ($_SERVER['REQUEST_METHOD'] == "POST" && $info = json_decode(file_get_contents("php://input"), true)) {
    if(!isset($info['id'])) respond(-1, "please send all parameters");
    $plan_id = $info['id'];
    if(stmt($conn, "DELETE FROM `plans` WHERE `id` = ?", "i", $plan_id))
        respond(1, "delete succefull");
    else respond(0, "خطای ناگهانی");
    mysqli_close($conn);
}