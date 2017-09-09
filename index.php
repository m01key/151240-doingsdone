<?php

require_once 'functions.php';

// показывать или нет выполненные задачи
$show_complete_tasks = rand(0, 1);

// устанавливаем часовой пояс в Московское время
date_default_timezone_set('Europe/Moscow');

$days = rand(-3, 3);
$task_deadline_ts = strtotime("+" . $days . " day midnight"); // метка времени даты выполнения задачи
$current_ts = strtotime('now midnight'); // текущая метка времени

// запишите сюда дату выполнения задачи в формате дд.мм.гггг
$date_deadline = date("d.m.Y", $task_deadline_ts);

// в эту переменную запишите кол-во дней до даты задачи
$days_until_deadline = ($task_deadline_ts - $current_ts)/86400;

// простой массив проектов
$projectArr = ['Все','Входящие','Учеба','Работа','Домашние дела','Авто'];

// простой массив с ассоциативным массивом внутри
$taskArr = [
  [
    'task' => 'Собеседование в IT компании',
    'doneDate' => '01.06.2018',
    'category' => 'Работа',
    'done' => 'Нет'
  ],
  [
    'task' => 'Выполнить тестовое задание',
    'doneDate' => '25.05.2018',
    'category' => 'Работа',
    'done' => 'Нет'
  ],
  [
    'task' => 'Сделать задание первого раздела',
    'doneDate' => '21.04.2018',
    'category' => 'Учеба',
    'done' => 'Да'
  ],
  [
    'task' => 'Встреча с другом',
    'doneDate' => '22.04.2018',
    'category' => 'Входящие',
    'done' => 'Нет'
  ],
  [
    'task' => 'Купить корм для кота',
    'doneDate' => 'Нет',
    'category' => 'Домашние дела',
    'done' => 'Нет'
  ],
  [
    'task' => 'Заказать пиццу',
    'doneDate' => 'Нет',
    'category' => 'Домашние дела',
    'done' => 'Нет'
  ]
];

$taskArrNew = [];
$projectArrLenght = count($projectArr);
$get = $_GET['project'];


if (isset($get)) {

  if (isset($projectArr[$get])) {

    $category = $projectArr[$get];

    foreach ($taskArr as $key => $value) {
      if ($value['category'] == $category) {
        $taskArrNew[] = $value;
      }
    }

  } else {
      http_response_code(404);
      print('<p>Ошибка 404</p>');
      exit();
  }

} else {
    $taskArrNew = $taskArr;
}

if ($show_complete_tasks == 0) {
  foreach ($taskArrNew as $key => $value) {
    if ($value['done'] == 'Да') {
      unset($taskArrNew[$key]);
    }
  }
}


$pageContentArr = [
  'tasks' => $taskArrNew,
  'date_deadline' => $date_deadline,
  'show_complete_tasks' => $show_complete_tasks,
  'days_until_deadline' => $days_until_deadline,
  'projects' => $projectArr
];

$pageContent = includeTemplate('templates/index.php', $pageContentArr);


$layoutContentArr = [
  'content' => $pageContent,
  'title' => 'Дела в порядке!',
  'projects' => $projectArr,
  'tasks' => $taskArr,
  'get' => $get
];

$layoutContent = includeTemplate('templates/layout.php', $layoutContentArr);




print($layoutContent);

?>







