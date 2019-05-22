
<?php
require_once('helpers.php');

date_default_timezone_set("Europe/Moscow");

$is_auth = rand(0, 1);

$user_name = 'Alex'; // укажите здесь ваше имя

$title = 'YetiCave - Главная страница'; //имя сраницы

$catigories = ["Доски и лыжи", "Крепления", "Ботинки", "Одежда", "Инструменты", "Разное"];
$index = 0;
$num = count($catigories);


$advert = [
    [
        "name" => "2014 Rossignol District Snowboard",
        "category" => "Доски и лыжи",
        "price" => "10999",
        "gif" => "img/lot-1.jpg"
    ],
    [
        "name" => "DC Ply Mens 2016/2017 Snowboard",
        "category" => "Доски и лыжи",
        "price" => "159999",
        "gif" => "img/lot-2.jpg"
    ],
    [
        "name" => "Крепления Union Contact Pro 2015 года размер L/XL",
        "category" => "Крепления",
        "price" => "8000",
        "gif" => "img/lot-3.jpg"
    ],
    [
        "name" => "Ботинки для сноуборда DC Mutiny Charocal",
        "category" => "Ботинки",
        "price" => "10999",
        "gif" => "img/lot-4.jpg"
    ],
    [
        "name" => "Куртка для сноуборда DC Mutiny Charocal",
        "category" => "Одежда",
        "price" => "7500",
        "gif" => "img/lot-5.jpg"
    ],
    [
        "name" => "Маска Oakley Canopy",
        "category" => "Разное",
        "price" => "5400",
        "gif" => "img/lot-6.jpg"
    ],
];

/**
 * Форматирует цену
 * @param $price
 * @return string
 */
function format_price($price) {
    return number_format(ceil($price), 0, null, ' ') . '₽';
}


//шаблоны
/*
$content = include_template('index.php', [
    'index' => $index,
    'num' => $num,
    'catigories' => $catigories,
    'advert' => $advert
]);

$layout_content = include_template('layout.php', [
    'is_auth' => $is_auth,
    'user_name' => $user_name,
    'content' => $content,
    'catigories' => $catigories,
    'title' => $title,
]);

print($layout_content);*/

//функция вычисляет оставшееся время
function show_date () {
    $ts = time();
    $ts_midnight = strtotime('tomorrow');
    $new_day = $ts_midnight - $ts;

    $hours = floor($new_day / 3600);
    $minutes = floor(($new_day % 3600) / 60);
    $main_time = $hours .':' . $minutes;
    $first_class = "timer--finishing";
    $class = "";

    if ($hours <= 1) {
        return array($main_time, $first_class);
    } else {
        return array($main_time, $class);
    }
}

//подключаемся к Mysql
$link = mysqli_connect("localhost", "root", "password","yeticave");
mysqli_set_charset($link, "utf8");
//проверяем соединение
if (!$link) {
    print("Ошибка подключения: " . mysqli_connect_error());
}
else {
    print("Соединение установлено");
    // выполнение запросов
}
//запрос на категории
$sql = "select * from catigories";

$result = mysqli_query($link, $sql);

if ($result) {
    $lots = mysqli_fetch_all($result, MYSQLI_ASSOC);
}
else {
    print("Ошибка подключения: " . mysqli_connect_error());
}
//запрос на лоты
$sql = "select * from lots where last_data > now() order by date_create desc";

if ($result) {
    $lots = mysqli_fetch_all($result, MYSQLI_ASSOC);
    $content = include_template('index.php', [
        'index' => $index,
        'num' => $num,
        'catigories' => $catigories,
        'advert' => $advert
    ]);

    //сценарий дя лот пхп
    print($lot_content = include_template('lot.php', [
        'advert' => $advert,
        'index' => $index,
        'content' => $content,
        'catigories' => $catigories,
        'num' => $num,
        'title' => 'YetiCave - Главная страница',
    ]));
}
else {
    print("Ошибка подключения: " . mysqli_connect_error());
}

print($layout_content = include_template('layout.php', [
    'is_auth' => $is_auth,
    'user_name' => $user_name,
    'content' => $content,
    'lot_content' => $lot_content,
    'catigories' => $catigories,
    'title' => 'YetiCave - Главная страница',
]));


