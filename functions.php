<?php
include './config.php';
function input_sec($input)
{
  $input = trim($input);
  $input = htmlspecialchars($input);
  return $input;
}

function level($grade, $times_json)
{
  if (!in_array($grade, [1, 2, 3])) {
    return "Invalid grade";
  }

  $times = json_decode($times_json, true);
  $time_week = 0;
  foreach ($times as $day => $daytime) {
    foreach ($daytime as $value) {
      if ($value === true) {
        $time_week++;
      }
    }
  }

  $levels = [
    [75, 90, 90],
    [50, 75, 90],
    [25, 50, 75],
    [10, 25, 50],
    [10, 10, 25]
  ];

  $index = min($time_week / 10 - 1, 4);

  return $levels[$index][$grade - 1];
}

function plan($times_json)
{
  $times = json_decode($times_json, true);
  $plan = [];

  foreach ($times as $day => $daytime) {
    $sum_time = count(array_filter($daytime, function ($value) {
      return $value === true;
    }));

    $learn = ($sum_time / 3) * 2;
    $practice = $sum_time / 3;

    foreach ($daytime as $key => $value) {
      if ($value === true && $learn >= 1) {
        $plan[$day][$key] = "learn";
        $learn--;
      } elseif ($value === true && $practice >= 1) {
        $plan[$day][$key] = "practice";
        $practice--;
      } else if ($value === false) {
        $plan[$day][$key] = "none";
      }
    }
  }

  return json_encode($plan);
}

function select_stmt($conn, $query, $types = "", ...$vars)
{
  $stmt = mysqli_prepare($conn, $query);
  if ($stmt) {
    if (!empty($types) && count($vars) === strlen($types)) {
      mysqli_stmt_bind_param($stmt, $types, ...$vars);
    }
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    // Get the number of rows
    $numRows = mysqli_num_rows($result);

    if ($numRows > 0) {
      // Fetch the rows using associated array
      $rows = [];
      while ($row = mysqli_fetch_assoc($result)) {
        $rows[] = $row;
      }

      mysqli_stmt_close($stmt);

      return $rows;
    } else {
      // No rows found
      mysqli_stmt_close($stmt);

      return false;
    }
  } else {
    // Handle the case when the statement preparation fails
    // You can return false or an appropriate error message based on your use case
    return false;
  }
}

function stmt($conn, $query, $types = "", ...$vars)
{
  $stmt = mysqli_prepare($conn, $query);
  if ($stmt) {
    if (!empty($types) && count($vars) === strlen($types)) {
      mysqli_stmt_bind_param($stmt, $types, ...$vars);
    }
    mysqli_stmt_execute($stmt);

    $affectedRows = mysqli_stmt_affected_rows($stmt);

    mysqli_stmt_close($stmt);

    // Return true if at least one row was affected, false otherwise
    return ($affectedRows > 0);
  } else {
    // Handle the case when the statement preparation fails
    // You can return false or an appropriate error message based on your use case
    return false;
  }
}

function add_plan_validation($times_json)
{
  $times = json_decode($times_json, true);
  $total_time = 0;

  foreach ($times as $day => $daytime) {
    $total_time += count(array_filter($daytime, function ($value) {
      return $value === true;
    }));
  }

  return $total_time < 24 ? -1 : ($total_time > 100 ? 1 : true);
}

function selectUser($username)
{
  global $conn;
  $user = select_stmt($conn, "SELECT * FROM `users` WHERE `username` = ?", "s", $username);
  if ($user) {
    return $user[0];
  }
  return false;
}

function selectUserPlans($userId)
{
  global $conn;
  $plans = select_stmt($conn, "SELECT `id`,`name`,`subject_id`,`level` FROM `plans` WHERE `user_id` = ?", "i", $userId);
  return formatPlans($plans);
}

function select_subplan($plan_id)
{
  global $conn;
  if ($subplan = select_stmt($conn, "SELECT `name`,`subject_id`,`plan` FROM `plans` WHERE `id` = ?", "i", $plan_id)) {
    $subplan = $subplan[0];
    $subject = select_stmt($conn, "SELECT `name` FROM `subjects` WHERE id = ?", "i", $subplan['subject_id'])[0];
    $subplan['subject'] = $subject['name'];
    unset($subplan['subject_id']);
    return $subplan;
  }
  return false;
}

function formatPlans($plans)
{
  global $conn;
  foreach ($plans as $key => $value) {
    $subject = select_stmt($conn, "SELECT `name` FROM `subjects` WHERE `id` = ?", "i", $value['subject_id'])[0];
    unset($value['subject_id']);
    $value['subject'] = $subject['name'];
    $plans[$key] = $value;
  }
  return $plans;
}

function respond($statuscode, $message)
{
  $respond = [
    "status" => $statuscode,
    "message" => $message
  ];
  die(json_encode($respond));
}

function signup_validation($username, $email, $password)
{
  if (!empty($username) && !empty($email) && strlen($password) > 8) {
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
      return true;
    } else return false;
  } else return false;
}

function update_user_validation($username, $email)
{
  if (!empty($username) && !empty($email)) {
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
      return true;
    } else return false;
  } else return false;
}
function login_validation($username, $email, $password)
{
  if ((!empty($username) xor !empty($email)) && !empty($password)) return true;
  else return false;
}
function encrypt_decrypt($action, $string, $secret_key)
{
  $output = false;
  $encrypt_method = "AES-256-CBC";
  //
  $secret_iv = 'This is my secret iv';
  // hash
  $key = hash('sha256', $secret_key);

  // iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
  $iv = substr(hash('sha256', $secret_iv), 0, 16);
  if ($action == 'encrypt') {
    $output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
    $output = base64_encode($output);
  } else if ($action == 'decrypt') {
    $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
  }
  return $output;
}

function generate_token($json_user_info)
{
  return encrypt_decrypt('encrypt', $json_user_info, "bozi");
}
