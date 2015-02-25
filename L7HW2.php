<?php
error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
ini_set('display_errors', 1);
header('Content-type: text/html; charset=utf-8');

function adsSave($ads_container, $sent_entry){  
    if ( isset( $sent_entry['return_id'] )  &&  is_numeric( $sent_entry['return_id'] ) ){
            $ads_container[$sent_entry['return_id']]=$sent_entry; 
    }else{
            $ads_container[]=$sent_entry;
    }
    return $ads_container;
}


function adsDelete($ads_container, $delete_id){ 
    unset($ads_container[$delete_id]); 
    return $ads_container;
}


function adsReturn($ads_container, $showform_params, $return_id){
            $return = $ads_container[$return_id];
            $showform_params = array(
                   'return_private' => "",
                   'return_company' =>"",
                   'namereturn' => $return['seller_name'],
                   'email_return' => $return['email'],
                   'phonereturn' => $return['phone'],
                   'city' => $return['location_id'],
                   'returncategory' => $return['category_id'],
                   'returntitle' =>$return['title'],
                   'returndescription' => $return['description'],
                   'returnprice' => $return['price'],
                   'return_id' => $return_id,
                   'notice_title_is_empty'=> ""
                                );
            if ($return['private']=='1'){
                    $showform_params['return_private'] = 'checked=""';
            }else{
                    $showform_params['return_company'] = 'checked=""';
            }
            $showform_params['return_send_email'] = (isset($return['allow_mails'])) ? 'checked=""' : '';
            
            return $showform_params;
}

function showForm($ads_container, $showform_params, $cities, $categories){ //блок вывода таблицы по шаблону 
          include './adsform.html';
}

function set_ads_cookie($ads_container){
             setcookie('ads', "", time()-3600);
             setcookie('ads', serialize($ads_container), time()+3600*24*7);
}



//конец блоков , запись массивов

$showform_params = array(
                        'return_private' => 'checked=""', 
                        'return_company' =>"",
                        'namereturn' => "",
                        'email_return' => "",
                        'return_send_email' => "",
                        'phonereturn' => "",
                        'city' => "",
                        'returncategory' => "",
                        'returntitle' => "",
                        'returndescription' => "",
                        'returnprice' => "0",
                        'notice_title_is_empty'=> "",
                        'return_id' => ""
                           );

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

//конец записи массивов, начало логики
if (isset($_COOKIE['ads'])){
  $ads_container =  unserialize($_COOKIE['ads']); 
}else{
  $ads_container="";  
}
  $ads_save_checker = $ads_container;

  
  
//button processing
   if ( isset($_POST['main_form_submit']) ) {    //send button
          if ( $_POST['title'] ){
             $ads_container = adsSave( $ads_container, $_POST );
          }else{
                $showform_params['notice_title_is_empty']='Введите название';
          }
   }elseif ( isset($_GET['delentry']) ) {           //delete button
         $ads_container = adsDelete( $ads_container, $_GET['delentry'] );
   }elseif ( isset($_GET['formreturn']) ) {         //return values to form button
         $showform_params = adsReturn( $ads_container, $showform_params, $_GET['formreturn'] );
   }
  
  
  
if ($ads_save_checker !== $ads_container){
  set_ads_cookie($ads_container);
}
   //вывод
   showForm($ads_container, $showform_params, $cities, $categories);
  ?>