<?php
session_start();

// подключаем функции
require_once 'userdata.php';
require_once 'functions.php';

// sdgsdgqwe
// показывать или нет выполненные задачи
// $show_complete_tasks = rand(0, 1);
// $show_complete_tasks = 0;

// устанавливаем часовой пояс в Московское время
date_default_timezone_set('Europe/Moscow');

// // запишите сюда дату выполнения задачи в формате дд.мм.гггг
// $date_deadline = date("d.m.Y", $task_deadline_ts);

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
    'doneDate' => '09.09.2017',
    'category' => 'Домашние дела',
    'done' => 'Нет'
  ],
  [
    'task' => 'Заказать пиццу',
    'doneDate' => '13.09.2017',
    'category' => 'Домашние дела',
    'done' => 'Нет'
  ]
];

$errorClass = 'form__input--error';
$errorEmpty = '<span class="form__error">Заполните это поле</span>';
$errorsss = [];

// новый массив задач
$taskArrNew = [];


if (isset($_SESSION["user"])) {

$usersName = $_SESSION["user"]['name'];

// параметры запроса - переменные
$projectGet = isset($_GET['project']) ? $_GET['project'] : NULL;
$addGet = isset($_GET['add']) ? $_GET['add'] : NULL;
$showCompletedGet = isset($_GET['show_completed']) ? $_GET['show_completed'] : NULL;
$show_complete_tasks = isset($_COOKIE['showCompleted']) ? $_COOKIE['showCompleted'] : NULL;


// проверяем параметр запроса -project-
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


if (isset($showCompletedGet)) {
  setcookie('showCompleted', $showCompletedGet);
  header("Location: /index.php");
}


if ($show_complete_tasks == 0 || $show_complete_tasks == NULL) {
  foreach ($taskArrNew as $key => $value) {
    if ($value['done'] == 'Да') {
      unset($taskArrNew[$key]);
    }
  }
}


// проверка массива на дедлайн
foreach ($taskArrNew as $key => $value) {
    $taskArrNew[$key] = check_deadline($value);
}

// обрабатываем форму по задачам
if ($_SERVER[REQUEST_METHOD] == 'POST') {

  $taskSubmit = isset($_POST['taskSubmit']) ? $_POST['taskSubmit'] : '';

  if ($taskSubmit) {

  $task = isset($_POST['name']) ? $_POST['name'] : '';
  $project = isset($_POST['project']) ? $_POST['project'] : '';
  $date = isset($_POST['date']) ? $_POST['date'] : '';
  $file = isset($_POST['file']) ? $_POST['file'] : '';
  $errorFormat = '<span class="form__error">Неверный формат даты</span>';


  $required = ['name', 'project', 'date'];
  $rules = ['date'];


  if ($task == '') {
    $errorsss[] = 'name';
  }
  if ($project == '') {
    $errorsss[] = 'project';
  }
  if ($date == '') {
    $errorsss[] = 'date';
  } else if (!strtotime($date)) {
    $errorsss[] = 'dateFormat';
  }
  if ($file) {
    $file_name = $_FILES['preview']['name'];
    $file_path = __DIR__.'/';
    move_uploaded_file($_FILES['preview']['tmp_name'], $file_path.$file_name);
  }

  if (!count($errorsss)) {

    $taskNew = [
    'task' =>  $task,
    'doneDate' => $date,
    'category' => $projectArr[$project],
    'done' => 'Нет'
    ];

    $taskNew = check_deadline($taskNew);
    array_unshift($taskArrNew, $taskNew);
  }

  }
}



// контент для формы
$formContentArr = [
  'task' => $task,
  'project' => $project,
  'date' => $date,
  'projectArr' => $projectArr,
  'errorsss' => $errorsss,
  'errorClass' => $errorClass,
  'errorEmpty' => $errorEmpty,
  'errorFormat' => $errorFormat

];


// проверям есть ли ошибки в форме или параметр запроса -эдд-
if (count($errorsss) || isset($addGet)) {
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
  'formContent' => $formContent,
  'usersName' => $usersName
];

// подключаем разметку
$layoutContent = includeTemplate('templates/layout.php', $layoutContentArr);

// отображаем разметку
print($layoutContent);

} else {

  $loginGet = isset($_GET['login']) ? $_GET['login'] : NULL;


  if ($_SERVER[REQUEST_METHOD] == 'POST') {

    $errorBadPassword = '<span>Вы ввели неверный пароль</span>';
    $errorBadEmail = '<span>Такой пользователь не найден</span>';

    $email = isset($_POST['email']) ? $_POST['email'] : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    $guestSubmit = isset($_POST['guestSubmit']) ? $_POST['guestSubmit'] : '';


    if ($guestSubmit) {

      if ($email == '') {
        $errorsss[] = 'email';
      }
      if ($password == '') {
        $errorsss[] = 'password';
      }

      if (!count($errorsss)) {
        if ($user = searchUserByEmail($email, $users)) {
          if (password_verify($password, $user['password'])) {
            $_SESSION['user'] = $user;
            header("Location: /index.php");
          } else {
            $errorsss[] = 'passwordBad';
          }
        } else {
          $errorsss[] = 'emailBad';
        }
      }

    }


  }

  $hidden = 'hidden';

  // проверяем параметр запроса -login- и ошибки
  if (isset($loginGet) || count($errorsss)) {
    $guestOverlay = 'overlay';
    $hidden = '';
  }

  $guestContentArr = [
    'guestOverlay' => $guestOverlay,
    'hidden' => $hidden,
    'errorBadPassword' => $errorBadPassword,
    'errorBadEmail' => $errorBadEmail,
    'email' => $email,
    'errorEmpty' => $errorEmpty,
    'errorClass' => $errorClass,
    'errorsss' => $errorsss
  ];

  $guestContent = includeTemplate('templates/guest.php', $guestContentArr);

  $layoutContentArr = [
    'guestContent' => $guestContent
  ];

  $layoutContent = includeTemplate('templates/layout.php', $layoutContentArr);

// отображаем разметку
  print($layoutContent);

}



?>
