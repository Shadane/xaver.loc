<?php
error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
ini_set('display_errors', 1);
header('Content-type: text/html; charset=utf-8');
if (!$config_arr = include('./config.php')){
        die('Unable to run configuration file');
}
   $db = new mysqli($config_arr['server_name'],$config_arr['user_name'],$config_arr['password'],$config_arr['database']);//устанавливаем соденинение
        if ( $db -> connect_errno > 0 ){
             die('Unable to connect'.$db->connect_error());
        }
   if (!$db ->set_charset('utf8')){
       die('Error while applying utf8 charset'.$db->error);
   }

$query="
DROP TABLE IF EXISTS `ads_container`;
CREATE TABLE `ads_container` (
  `id` smallint(6) NOT NULL AUTO_INCREMENT,
  `private` tinyint(1) NOT NULL,
  `seller_name` varchar(20) NOT NULL,
  `email` varchar(40) NOT NULL,
  `allow_mails` bit(1) NOT NULL DEFAULT b'0',
  `phone` char(11) NOT NULL,
  `location_id` char(6) NOT NULL,
  `category_id` char(3) NOT NULL,
  `title` varchar(30) NOT NULL,
  `description` varchar(500) NOT NULL,
  `price` varchar(9) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
DROP TABLE IF EXISTS `categories`;
CREATE TABLE `categories` (
  `id` tinyint(4) NOT NULL AUTO_INCREMENT,
  `cat_name` varchar(20) NOT NULL,
  `subcat_id` varchar(3) NOT NULL,
  `subcat_name` varchar(30) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
INSERT INTO `categories` (`id`, `cat_name`, `subcat_id`, `subcat_name`) VALUES
(1,	'Транспорт',	'9',	'Автомобили с пробегом'),
(2,	'Транспорт',	'109',	'Новые автомобили'),
(3,	'Транспорт',	'14',	'Мотоциклы и мототехника'),
(4,	'Транспорт',	'81',	'Грузовики и спецтехника'),
(5,	'Транспорт',	'11',	'Водный транспорт'),
(6,	'Транспорт',	'10',	'Запчасти и аксессуары'),
(7,	'Недвижимость',	'24',	'Квартиры'),
(8,	'Недвижимость',	'23',	'Комнаты'),
(9,	'Недвижимость',	'25',	'Дома, дачи, коттеджи'),
(10,	'Недвижимость',	'26',	'Земельные участки'),
(11,	'Недвижимость',	'85',	'Гаражи и машиноместа'),
(12,	'Недвижимость',	'42',	'Коммерческая недвижимость'),
(13,	'Недвижимость',	'86',	'Недвижимость за рубежом'),
(14,	'Работа',	'111',	'Вакансии (поиск сотрудников)'),
(15,	'Работа',	'112',	'Резюме (поиск работы)'),
(16,	'Услуги',	'114',	'Предложения услуг'),
(17,	'Услуги',	'115',	'Запросы на услуги'),
(18,	'Личные вещи',	'27',	'Одежда, обувь, аксессуары'),
(19,	'Личные вещи',	'29',	'Детская одежда и обувь'),
(20,	'Личные вещи',	'30',	'Товары для детей и игрушки'),
(21,	'Личные вещи',	'28',	'Часы и украшения'),
(22,	'Личные вещи',	'88',	'Красота и здоровье'),
(23,	'Для дома и дачи',	'21',	'Бытовая техника'),
(24,	'Для дома и дачи',	'20',	'Мебель и интерьер'),
(25,	'Для дома и дачи',	'87',	'Посуда и товары для кухни'),
(26,	'Для дома и дачи',	'82',	'Продукты питания'),
(27,	'Для дома и дачи',	'19',	'Ремонт и строительство'),
(28,	'Для дома и дачи',	'106',	'Растения'),
(29,	'Бытовая электроника',	'32',	'Аудио и видео'),
(30,	'Бытовая электроника',	'97',	'Игры, приставки и программы'),
(31,	'Бытовая электроника',	'31',	'Настольные компьютеры'),
(32,	'Бытовая электроника',	'98',	'Ноутбуки'),
(33,	'Бытовая электроника',	'99',	'Оргтехника и расходники'),
(34,	'Бытовая электроника',	'96',	'Планшеты и электронные книги'),
(35,	'Бытовая электроника',	'84',	'Телефоны'),
(36,	'Бытовая электроника',	'101',	'Товары для компьютера'),
(37,	'Бытовая электроника',	'105',	'Фототехника'),
(38,	'Хобби и отдых',	'33',	'Билеты и путешествия'),
(39,	'Хобби и отдых',	'34',	'Велосипеды'),
(40,	'Хобби и отдых',	'83',	'Книги и журналы'),
(41,	'Хобби и отдых',	'36',	'Коллекционирование'),
(42,	'Хобби и отдых',	'38',	'Музыкальные инструменты'),
(43,	'Хобби и отдых',	'102',	'Охота и рыбалка'),
(44,	'Хобби и отдых',	'39',	'Спорт и отдых'),
(45,	'Хобби и отдых',	'103',	'Знакомства'),
(46,	'Животные',	'89',	'Собаки'),
(47,	'Животные',	'90',	'Кошки'),
(48,	'Животные',	'91',	'Птицы'),
(49,	'Животные',	'92',	'Аквариум'),
(50,	'Животные',	'93',	'Другие животные'),
(51,	'Животные',	'94',	'Товары для животных'),
(52,	'Для бизнеса',	'116',	'Готовый бизнес'),
(53,	'Для бизнеса',	'40',	'Оборудование для бизнеса');
DROP TABLE IF EXISTS `cities`;
CREATE TABLE `cities` (
  `city_id` char(6) NOT NULL,
  `city_name` varchar(20) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
INSERT INTO `cities` (`city_id`, `city_name`) VALUES
('641780',	'Новосибирск'),
('641490',	'Барабинск'),
('641510',	'Бердск'),
('641600',	'Искитим'),
('641630',	'Колывань'),
('641680',	'Краснообск'),
('641710',	'Куйбышев'),
('641760',	'Мошково'),
('641790',	'Обь'),
('641800',	'Ордынское'),
('641970',	'Черепаново')";
$query=  explode(';', $query);

foreach ($query as $number=>$actual_query){
   if (!$db->query($actual_query)){
       die('Error during query: '.$db->error);
   }
}
$db->close();
if (!is_dir('./smarty/templates')){
mkdir('./smarty/templates');
}else{
    echo 'директория уже существует, переношу файл';
}


if (is_file('./L9MySQLi.tpl')){
copy('./L9MySQLi.tpl', './smarty/templates/L9MySQLi.tpl') or die('не удалось переместить файл index.tpl');
}


echo '<div style="width: 300px;align= right"><a href=/L9MySQLi.php>Установка завершена успешно!</a></div>';



?>
<!--<html>
    <form style="" method="post">
        <label>Server name:</label></br>
        <input type="text" name="server_name">
        </br></br>
         <label>User name:</label></br>
        <input type="text" name="user_name">
        </br></br>
         <label>Password:</label></br>
        <input type="text" name="password">
        </br></br>
         <label>Database:</label></br>
        <input type="text" name="database">
        </br></br>
        <input type="submit" name="install" value="install">
    </form>
</html>-->