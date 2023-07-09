<?php
require_once "./config.php";
require_once "./functions.php";
header('Content-Type: application/json');

// Retrieve POST data
if (isset($_POST['username']) && isset($_POST['email']) && isset($_POST['password'])) {
    $username = input_sec($_POST['username']);
    $email = input_sec($_POST['email']);
    $password = input_sec($_POST['password']);
}
// Hash the password for security
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

$result = mysqli_query($conn, "select * from users");
while($allUsers = mysqli_fetch_assoc($result)){
    if($allUsers['email'] == $email || $allUsers['username'] == $username){
        $respond=[
            "status" => "error",
            "message" => "User with same username or email already exists."
        ];
        die(json_encode($respond));
    }
}
$stmt = mysqli_prepare($conn,"INSERT INTO users (username,email,password) VALUES (?,?,?)");
mysqli_stmt_bind_param($stmt,'sss',$username,$email,$hashedPassword);
mysqli_stmt_execute($stmt);
if(mysqli_stmt_affected_rows($stmt) > 0) 
    $respond = [
        "status" => "sucssess",
        "message" => "Sign-up successful."
    ];
else
$respond = [
    "status" => "Error",
    "message" => "Failed to sign up."
];
// Close the statement and database connection
mysqli_stmt_close($stmt);
mysqli_close($conn);

// Return the JSON response
echo json_encode($respond);

?>