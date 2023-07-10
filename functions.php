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

function insert_stmt($conn, $query, $types = "", ...$vars)
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
