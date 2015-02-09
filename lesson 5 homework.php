<?php
error_reporting(E_ALL | E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
ini_set('display_errors', 1);

function news_all() { //функция вывода всего списка новостей
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
    ob_end_clean();
    header('HTTP/1.1 404 Not Found');
    require '404.php';
    exit;
}

//следующая функция обрабатывает и гет и пост.
function post_get($arr) {
    global $news;
    if (in_array('id', array_flip($arr))) { //у $_POST в данном случае всегда будет параметр 'id'
        if (is_numeric($arr['id']) && $arr['id'] <= (count($news)) && $arr['id'] > 0) {
            news_specific($arr['id'] - 1);
        } else {
            news_all();
        }
    } elseif ($arr) {
        not_found();
    } else {
        news_all();
    }
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

post_get($_GET);

echo '<br/> Дальше ПОСТ <br/><br/><br/><br/><br/>';
post_get($_POST);
// Функция вывода всего списка новостей.
// Функция вывода конкретной новости.
// Точка входа.
// Если новость присутствует - вывести ее на сайте, иначе мы выводим весь список
// Был ли передан id новости в качестве параметра?
// если параметр не был передан - выводить 404 ошибку
// http://php.net/manual/ru/function.header.php
?>

<form method='POST'>
    <label>
        <p> <input type="text" name="id">
        </p>
        <p> <input type="submit">
        </p>
    </label>
</form>
