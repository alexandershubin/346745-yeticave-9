
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

}
else {
    print("Ошибка подключения: " . mysqli_connect_error());
}

if (isset($_GET['id'])) {
    $lotId = $_GET['id'];
} else {
    http_response_code(404);
    header("Location: pages/404.html");
}

$lotsIdsSql = "SELECT id FROM lots";
$ids = getMysqlSelectionResult($link, $lotsIdsSql);

if (!isInArray($ids, $lotId)) {
    http_response_code(404);
    header("Location: pages/404.html");
}

$lotSql = "SELECT l.name_lot AS name, user_id,
            c.name_catigories AS catigory, about, current_cost,
            rate_step, image, end_at 
            FROM lots AS l JOIN catigories AS c
            ON c.id = catigory_id 
            WHERE l.id = $lotId";

$categoriesSql = "SELECT name FROM catigories ORDER BY id";

$ratesSql = "SELECT user_id, r.cost, u.full_name AS user_name, 
             r.created_at AS rate_time 
             FROM rates AS r JOIN users AS u
             ON u.id = user_id
             WHERE r.lot_id = $lotId
             ORDER BY r.created_at DESC";

$lot = getMysqlSelectionAssocResult($link, $lotSql);
$categories = getMysqlSelectionResult($link, $categoriesSql);
$rates = getMysqlSelectionResult($link, $ratesSql);
tagsTransforming('strip_tags', $lot, $categories, $rates);

$ratesCount = empty($rates) ? 0 : count($rates);
$showRate = true;

if (!isset($_SESSION['user_id'])) {
    $showRate = false;
} else {
    $userId = $_SESSION['user_id'];
    if ($lot['user_id'] === $userId) {
        $showRate = false;
    } elseif (isset($rates[0]['user_id'])) {
        $lastRateUserId = $rates[0]['user_id'];
        $showRate = $lastRateUserId === $userId ? false : true;
    } elseif (strtotime($lot['end_at']) < time()) {
        $showRate = false;
    }
}

$minRate = $lot['current_cost'] + $lot['rate_step'];

$contentAdress = 'lot.php';
$contentValues = [ 'advert' => $advert,
    'lot' => $advert,
    'lotId' => $lotId,
    'minRate' => $minRate,
    'rates' => $rates,
    'ratesCount' => $ratesCount,
    'showRate' => $showRate,
    'cost' => '',
    'success' => '',
    'errors' => []
];

if (isset($_POST['submit']) && $showRate) {
    $errors = [];
    $cost = (int) $_POST['cost'];

    if (empty($cost)) {
        $errors['cost'] = 'Поле должно быть заполнено!';
    } elseif ($cost < $minRate || $cost != round($cost)) {
        $errors['cost'] = 'Введите корректную цену!';
    }

    $contentValues['cost'] = $cost;
    $contentValues['errors'] = $errors;

    if (empty($errors)) {
        $userId = $_SESSION['user_id'];
        $rateData = [$cost, $userId, $lotId];
        $rateSql = "INSERT INTO rates 
                (cost, user_id, lot_id)
                VALUES (?, ?, ?)";
        $lotData = [$cost, $lotId];
        $lotSql = "UPDATE lots SET current_cost = ? WHERE id = ?";
        $newRate = insertDataMysql($link, $rateSql, $rateData);
        $updatedLot = insertDataMysql($link, $lotSql, $lotData);

        $contentValues['success'] = 'Ставка успешно добавлена';
    }
}

$pageContent = include_template($contentAdress, $contentValues);

$layoutAdress = 'layout.php';
$layoutValues = [
    'pageTitle' => $advert['name'],
    'isAuth' => $is_auth,
    'userName' => $user_name,
    'categories' => $categories,
    'pageContent' => $pageContent
];

$pageLayout = include_template($layoutAdress, $layoutValues);

print $pageLayout;



