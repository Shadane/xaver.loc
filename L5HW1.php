<?php
error_reporting(E_ALL | E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
ini_set('display_errors', 1);
header('Content-type: text/html; charset=utf-8');

function news_all() { //функция вывода всего списка новостей, ради интереса попробовал через for
    global $news;
    for ($i = 0; $i < count($news); $i++) {
        echo ($i + 1) . '. ' . $news[$i] . '<br/>';
    }
}

function news_specific($id) {
    global $news;
    echo ($id + 1) . '. ' . $news[$id] . '<br/>' . '<br/>' . '<br/>';
}

function not_found() {
//    ob_end_clean();
    header('HTTP/1.0 404 Not Found');
    require '404.php';
    exit;
}

$news = 'Четыре новосибирские компании вошли в сотню лучших работодателей
Выставка университетов США: открой новые горизонты
Оценку «неудовлетворительно» по качеству получает каждая 5-я квартира в новостройке
Студент-изобретатель раскрыл запутанное преступление
Хоккей: «Сибирь» выстояла против «Ак Барса» в пятом матче плей-офф
Здоровое питание: вегетарианская кулинария
День святого Патрика: угощения, пивной теннис и уличные гуляния с огнем
«Красный факел» пустит публику на ночные экскурсии за кулисы и по закоулкам столетнего здания
Звезды телешоу «Голос» Наргиз Закирова и Гела Гуралиа споют в «Маяковском»';
$news = explode("\n", $news);

if (isset($_GET['id'])){
    $get_holder = $_GET['id'];
    if (is_numeric($get_holder) && $get_holder <= (count($news)) && $get_holder > 0) {
            news_specific($get_holder - 1);
        } else {
            news_all();
        }
    } elseif ($_GET) {
        not_found();
    } else {
        news_all();
    }
?>