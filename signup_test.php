<?php
/*
// Hash the password for security
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);


// Prepare and execute the SQL statement to check if the username or email already exists
$stmt = $conn->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
$stmt->bind_param("ss", $username, $email);
$stmt->execute();
$stmt->store_result();

// Check if the username or email already exists
if ($stmt->num_rows > 0) {
    // User with same username or email already exists
    $response = array(
        "status" => "error",
        "message" => "User with same username or email already exists."
    );
} else {
    // Prepare and execute the SQL statement to insert a new user
    $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $email, $hashedPassword);
    $stmt->execute();

    // Check if the record was inserted successfully
    if ($stmt->affected_rows > 0) {
        // Sign-up successful
        $response = array(
            "status" => "success",
            "message" => "Sign-up successful."
        );
    } else {
        // Failed to sign up
        $response = array(
            "status" => "error",
            "message" => "Failed to sign up."
        );
    }

    if ((!isset($username) || empty($username)) || (!isset($email) || empty($email)) ||(!isset($password) || empty($password))) {
        $response = array(
            "status" => "error",
            "message" => "please enter all of the inputs"
        );
    }
}

// Close the statement and database connection
$stmt->close();
$conn->close();

// Return the JSON response
header('Content-Type: application/json');
echo json_encode($response);*/