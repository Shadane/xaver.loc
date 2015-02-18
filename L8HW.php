<?php
error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
ini_set('display_errors', 1);
header('Content-type: text/html; charset=utf-8');
session_start();

$project_root=$_SERVER['DOCUMENT_ROOT'];
$smarty_dir = $project_root.'/smarty';

require($smarty_dir.'/libs/Smarty.class.php');
$smarty = new Smarty();

$smarty->compile_check = true;
$smarty->debugging = false;

$smarty->template_dir = $smarty_dir.'/templates';
$smarty->compile_dir = $smarty_dir.'/templates_c';
$smarty->cache_dir = $smarty_dir.'/cache';
$smarty->config_dir = $smarty_dir.'/configs';

$radios=array('Частное лицо','Компания');



$cities = array(
           '641780' => 'Новосибирск', 
           '641490' => 'Барабинск', 
           '641510' => 'Бердск', 
           '641600' => 'Искитим', 
           '641630' => 'Колывань', 
           '641680' => 'Краснообск', 
           '641710' => 'Куйбышев', 
           '641760' => 'Мошково', 
           '641790' => 'Обь',
           '641800' => 'Ордынское',
           '641970' => 'Черепаново');

 $categories = array(
        'Транспорт' => array('9' => 'Автомобили с пробегом', '109' => 'Новые автомобили', '14' => 'Мотоциклы и мототехника', '81' => 'Грузовики и спецтехника', '11' => 'Водный транспорт', '10' => 'Запчасти и аксессуары'),
        'Недвижимость' => array('24' => 'Квартиры', '23' => 'Комнаты', '25' => 'Дома, дачи, коттеджи', '26' => 'Земельные участки', '85' => 'Гаражи и машиноместа', '42' => 'Коммерческая недвижимость', '86' => 'Недвижимость за рубежом'),
        'Работа' => array('111' => 'Вакансии (поиск сотрудников)', '112' => 'Резюме (поиск работы)'),
        'Услуги' => array('114' => 'Предложения услуг', '115' => 'Запросы на услуги'),
        'Личные вещи' => array('27' => 'Одежда, обувь, аксессуары', '29' => 'Детская одежда и обувь', '30' => 'Товары для детей и игрушки', '28' => 'Часы и украшения', '88' => 'Красота и здоровье'),
        'Для дома и дачи' => array('21' => 'Бытовая техника', '20' => 'Мебель и интерьер', '87' => 'Посуда и товары для кухни', '82' => 'Продукты питания', '19' => 'Ремонт и строительство', '106' => 'Растения'),
        'Бытовая электроника' => array('32' => 'Аудио и видео', '97' => 'Игры, приставки и программы', '31' => 'Настольные компьютеры', '98' => 'Ноутбуки', '99' => 'Оргтехника и расходники', '96' => 'Планшеты и электронные книги', '84' => 'Телефоны', '101' => 'Товары для компьютера', '105' => 'Фототехника'),
        'Хобби и отдых' => array('33' => 'Билеты и путешествия', '34' => 'Велосипеды', '83' => 'Книги и журналы', '36' => 'Коллекционирование', '38' => 'Музыкальные инструменты', '102' => 'Охота и рыбалка', '39' => 'Спорт и отдых', '103' => 'Знакомства'),
        'Животные' => array('89' => 'Собаки', '90' => 'Кошки', '91' => 'Птицы', '92' => 'Аквариум', '93' => 'Другие животные', '94' => 'Товары для животных'),
        'Для бизнеса' => array('116' => 'Готовый бизнес', '40' => 'Оборудование для бизнеса')
                  );


if (is_file('./adsholder.txt')){
    
$ads_container= unserialize(file_get_contents('./adsholder.txt'));
  }
  else{
      $ads_container= "";
  }
  $ads_save_checker=$ads_container;
  
    $showform_params = array(
                        'return_private' => "0", 
                        'namereturn' => "",
                        'email_return' => "",
                        'return_send_email' => "",
                        'phonereturn' => "",
                        'city' => "",
                        'returncategory' => "",
                        'returntitle' => "",
                        'returndescription' => "",
                        'returnprice' => "0",
                        'notice_field_is_empty'=> ""
                           );
  
   if (isset($_POST['main_form_submit'])) { //блок проверки какая кнопка была нажата. Если Отправить, то передаем записи из поста в сессию 
       if (($_POST['title'])){
           if (isset($_SESSION['save_changes_id'])){ //при нажатой кнопке Отправить если в сессии сохранен ключ редактируемого обьявления, то сохраняем в него вместо создания нового.
               $ads_container[$_SESSION['save_changes_id']]=$_POST; 
               $showform_params['notice_edit_success'] = 'Объявление успешно отредактировано';
               unset($_SESSION['save_changes_id']); //удаляем ключ в сессии, тем самым закрываем редактирование в сессии. Однако если после этого нажать f5 и повторно отправить, то ключа уже нет и создается новое. если делать редирект, то в коде много что менять.
           } else {
           $ads_container[]=$_POST;
           }
       }else {
           $showform_params['notice_field_is_empty']='Введите название';
       }
   }elseif (isset($_GET['delentry'])) {           //если нажата кнопка удалить
       unset($ads_container[$_GET['delentry']]);  
       if (isset($_SESSION['save_changes_id'])){  //вот тут приходится еще раз проверять и удалять, иначе потыкавшись можно будет найти баг.
               unset($_SESSION['save_changes_id']); //можно это условие выписать отдельно для обеих кнопок по типу if button1 || button 2 pressed -> unset. для двух кнопок не стал так делать.
       }
   }elseif (isset($_GET['formreturn'])) {        //если нажали на название обьявления, то заполняем массив $showform_params
     $return_id = $ads_container[$_GET['formreturn']];
       $showform_params = array(
           'return_private' => $return_id['private'],
           'namereturn' => $return_id['seller_name'],
           'email_return' => $return_id['email'],
           'phonereturn' => $return_id['phone'],
           'city' => $return_id['location_id'],
           'returncategory' => $return_id['category_id'],
           'returntitle' =>$return_id['title'],
           'returndescription' => $return_id['description'],
           'returnprice' => $return_id['price'],
           'notice_field_is_empty'=> ""
                                );
            $showform_params['return_send_email'] = (isset($return_id['allow_mails'])) ? 'checked=""' : '';//закончили заполнение массива
            /* решил сохранять в сессии, но можно создать еще отдельно файл, 
             * подумал что в файле с обьявлениями сохранять не очень опитимизированно будет,
             * т.к лишние перезаписи.
             */
            $_SESSION['save_changes_id'] = $_GET['formreturn']; 
   }
   if ($ads_container!==$ads_save_checker){//ведем запись в файл только если что-то было изменено
  file_put_contents('./adsholder.txt', serialize($ads_container));
   }
$smarty->assign('radios',$radios);
$smarty->assign('ads_container',$ads_container);
$smarty->assign('cities',$cities);
$smarty->assign('categories',$categories);
$smarty->assign('showform_params', $showform_params);
$smarty->display('index.tpl');