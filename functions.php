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
    $result = null;
    foreach ($users as $user) {
        if ($user['email'] == $email) { $result = $user;
        break;
        }
    }
    return $result;
}



?>
