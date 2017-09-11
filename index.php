<?php

// подключаем функции
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

// новый массив задач
$taskArrNew = [];
// параметры запроса
$projectGet = isset($_GET['project']) ? $_GET['project'] : NULL;
$addGet = isset($_GET['add']) ? $_GET['add'] : NULL;


// проверяем параметр запроса -проджект-
if (isset($projectGet)) {
  if (isset($projectArr[$projectGet])) {
    $category = $projectArr[$projectGet];
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

// показываем или нет выполненные задачи
if ($show_complete_tasks == 0) {
  foreach ($taskArrNew as $key => $value) {
    if ($value['done'] == 'Да') {
      unset($taskArrNew[$key]);
    }
  }
}


// обрабатываем форму
if ($_SERVER[REQUEST_METHOD] == 'POST') {

  $task = isset($_POST['name']) ? $_POST['name'] : '';
  $project = isset($_POST['project']) ? $_POST['project'] : '';
  $date = isset($_POST['date']) ? $_POST['date'] : '';
  $file = isset($_POST['file']) ? $_POST['file'] : '';

  $required = ['name', 'project', 'date'];
  $rules = ['date'];
  $errors = 0;
  $errorClass = 'form__input--error';
  $errorEmpty = '<span class="form__error">Заполните это поле</span>';
  $errorFormat = '<span class="form__error">Неверный формат даты</span>';

  if ($task == '') {
    $errorTask = $errorClass;
    $errorTextTask = $errorEmpty;
    $errors = 1;
  }
  if ($project == '') {
    $errorProject = $errorClass;
    $errorTextProject = $errorEmpty;
    $errors = 1;
  }
  if ($date == '') {
    $errorDate = $errorClass;
    $errorTextDate = $errorEmpty;
    $errors = 1;
  } else if (!strtotime($date)) {
    $errorDate = $errorClass;
    $errorTextDate = $errorFormat;
    $errors = 1;
  }
  if ($file) {
    $file_name = $_FILES['preview']['name'];
    $file_path = __DIR__.'/';
    move_uploaded_file($_FILES['preview']['tmp_name'], $file_path.$file_name);
  }
  if (!$errors) {
    $taskNew = [
    'task' =>  $task,
    'doneDate' => $date,
    'category' => $projectArr[$project],
    'done' => 'Нет'
    ];
    array_unshift($taskArrNew, $taskNew);
    array_unshift($taskArr, $taskNew);
  }

}

// контент для формы
$formContentArr = [
  'task' => $task,
  'project' => $project,
  'date' => $date,
  'errorTask' => $errorTask,
  'errorTextTask' => $errorTextTask,
  'errorDate' => $errorDate,
  'errorTextDate' => $errorTextDate,
  'errorProject' => $errorProject,
  'errorTextProject' => $errorTextProject,
  'projectArr' => $projectArr
];

// var_dump($errors);

// проверям есть ли ошибки в форме или параметр запроса -эдд-
if (($errors) || isset($addGet)) {
  $overlay = 'class="overlay"';
  $formContent = includeTemplate('templates/form.php', $formContentArr);
}



// массив данных для главной страницы
$pageContentArr = [
  'tasks' => $taskArrNew,
  'date_deadline' => $date_deadline,
  'show_complete_tasks' => $show_complete_tasks,
  'days_until_deadline' => $days_until_deadline,
  'projects' => $projectArr
];
// подключаем главную страницу
$pageContent = includeTemplate('templates/index.php', $pageContentArr);

// массив данных для разметки
$layoutContentArr = [
  'content' => $pageContent,
  'title' => 'Дела в порядке!',
  'projects' => $projectArr,
  'tasks' => $taskArr,
  'projectGet' => $projectGet,
  'addGet' => $addGet,
  'overlay' => $overlay,
  'formContent' => $formContent
];
// подключаем разметку
$layoutContent = includeTemplate('templates/layout.php', $layoutContentArr);

// отображаем разметку
print($layoutContent);



?>







