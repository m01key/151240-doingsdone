<?php

function includeTemplate($file, $array) {

  if (is_file($file)) {
    ob_start();

    extract($array);
    require_once $file;

    $html = ob_get_clean();
    return $html;
  }
  return '';
}


function taskCount($taskAll, $projectName) {
  $taskAmount = 0;
  if ($projectName == 'Все') {
    return count($taskAll);
  }
  foreach ($taskAll as $key => $value) {
    if ($value['category'] == $projectName) {
      $taskAmount++;
    }
  }
  return $taskAmount;
}


function searchUserByEmail($email, $users) {

    foreach ($users as $user) {
        if ($user['email'] == $email) {
            return $user;
        }
    }
    return false;
}


function check_deadline($date) {

    $current_ts = strtotime('now midnight');
    $task_deadline_ts = strtotime($date['doneDate']);
    $days_until_deadline = ($task_deadline_ts - $current_ts)/86400;
    if ($days_until_deadline <= 1) {
        $date['deadline'] = true;
    }
    return $date;
}

?>
