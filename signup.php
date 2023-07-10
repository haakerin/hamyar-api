<?php
require_once "./config.php";
require_once "./functions.php";

// Retrieve POST data
if ($_SERVER['REQUEST_METHOD'] == "POST" && $info = json_decode(file_get_contents("php://input"), true)) {
    if (!isset($info['username'], $info['email'], $info['password'])) {
        $respond = [
            "status" => -1,
            "message" => "please send all parameters (username, email, password)"
        ];
        die(json_encode($respond));
    }
    $username = input_sec($info['username']);
    $email = input_sec($info['email']);
    $password = input_sec($info['password']);
}
// Hash the password for security
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

$result = mysqli_query($conn, "select * from users");
while ($allUsers = mysqli_fetch_assoc($result)) {
    if ($allUsers['email'] == $email || $allUsers['username'] == $username) {
        $respond = [
            "status" => -2,
            "message" => "کاربری با این مشخصات وجود دارد"
        ];
        die(json_encode($respond));
    } 
}

if (insert_stmt($conn, "INSERT INTO users (username,email,password) VALUES (?,?,?)", "sss", $username, $email, $hashedPassword))
    $respond = [
        "status" => 1,
        "message" => "Sign-up successful."
    ];
else
    $respond = [
        "status" => 0,
        "message" => "خطا در ثبت نام"
    ];
// Close the statement and database connection
mysqli_stmt_close($stmt);
mysqli_close($conn);

// Return the JSON response
echo json_encode($respond);
