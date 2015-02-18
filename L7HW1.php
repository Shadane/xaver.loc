<?php
error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
ini_set('display_errors', 1);
header('Content-type: text/html; charset=utf-8');
session_start();
//блоки

function include_city_options(){
    global $cities,$showform_params;
    foreach ($cities as $key => $value) {
        $selected = ($key == $showform_params["city"]) ? 'selected=""' : '';
        echo ' <option data-coords=",,"' . $selected . ' value="' . $key . '">' . $value . '</option>';
    }
}

function include_category_options(){
    global $categories,$showform_params;
    foreach ($categories as $category => $subarray) { //цикл для вывода категорий и субкатерогий в выпадающем списке
        echo '<optgroup label="' . $category . '">';
        foreach ($subarray as $key => $subcatname) {
            $selected = ($key == $showform_params["returncategory"]) ? 'selected=""' : '';
            echo '<option ' . $selected . ' value="' . $key . '">' . $subcatname;
        }
    }
}

function show_table_contents(){ //блок вывода таблицы по шаблону 
// Вывод таблицы из массива.
   global $ads_container;
    if ($ads_container) {     
        foreach ($ads_container as $key => $array) {
                echo '<tr>';
                echo '<td> |  <a href="?formreturn=' . $key . '"> ' . $array["title"] . '</td>';
                echo '<td>  |  ' . $array['price'] . '</td>';
                echo '<td>  |  ' . $array['seller_name'] . '</td>';
                echo '<td>  |  <a href="?delentry=' . $key . '">Удалить</a> |</td>';
                echo '</tr>';
            }
        }
    
    ?>
                                      </table>
    <?php
}

function show_form($showform_params) { 
?>
<html>
    <head>  
        <style>  
        div { width: 800px;}
        </style>  
    </head>  
    <form  method="post">
        <div style="margin-left:220px;margin-top:10px"> 
            <label><input type="radio" <?php echo $showform_params['return_private'];?> value="1" name="private">Частное лицо</label>
            <label style="margin-left:20px"><input type="radio" value="0" <?php echo $showform_params['return_company'];?> name="private">Компания</label>
        </div>
        <div style="margin-left:60px;margin-top:10px"> 
            <label><b>Ваше имя</b></label>
            <input style="margin-left:85px; width:230px" type="text" maxlength="40" value="<?php echo  $showform_params["namereturn"] ?>" name="seller_name">
        </div>
        <div style="margin-left:60px;  margin-top:10px"> 
            <label>Электронная почта</label>
            <input style="margin-left:27px; width:230px;" type="text"  value=" <?php echo $showform_params["email_return"]; ?> " name="email" id="fld_email">
        </div>
        <div style="margin-left:217px;  margin-top:10px">
            <label><input type="checkbox"<?php echo $showform_params["return_send_email"]; ?>  value="1" name="allow_mails" id="allow_mails" class="form-input-checkbox">
                Я не хочу получать вопросы по объявлению по e-mail</label> 
        </div>
        <div style="margin-left:60px;  margin-top:10px"> 
            <label>Номер телефона</label> 
            <input style="margin-left:46px; width:230px" type="text"  value="<?php echo $showform_params["phonereturn"]; ?>" name="phone">
        </div>
        <div style="margin-left:60px;  margin-top:10px"> 
            <label >Город</label> 
            <select style="margin-left:118px; width:230px;height:22px" title="Выберите Ваш город" name="location_id"> 
                <option>-- Выберите город --</option>
                <option disabled="disabled">-- Города --</option>
                <?php include_city_options(); ?>
                <option>Выбрать другой...</option>
            </select>
        </div>
        <div style="margin-left:60px;  margin-top:10px"> 
            <label for="fld_category_id" class="form-label">Категория</label> 
            <select style="margin-left:89px; width:230px;height:22px" name="category_id">
                <option value="">-- Выберите категорию --</option>
                <?php include_category_options();?>
            </select> 
        </div>
        <div style="margin-left:60px;  margin-top:10px">
            <label>Название объявления</label> 
            <input style="margin-left:12px; width:230px;" type="text" maxlength="50" placeholder="<?php echo $showform_params['notice_field_is_empty']. '" value="' . $showform_params["returntitle"]; ?>" name="title">
        </div>
        <div style="margin-left:60px;  margin-top:10px"> 
            <label style="position:absolute">Описание объявления</label>
            <textarea style="margin-left:162px; width:230px;height:70px;" maxlength="3000" name="description" ><?php echo $showform_params["returndescription"]; ?></textarea></div>
        <div style="margin-left:60px;  margin-top:10px"> 
            <label >Цена</label>
            <input style="margin-left:124px; width:230px" type="text" maxlength="9"  value="<?php echo $showform_params["returnprice"]; ?> " name="price" >
            <div>
                <div style="margin-left:161px;  margin-top:10px"> 
                <input style="height:30px;font-weight: 700;color:white;border-radius: 3px;background: rgb(64,199,129);box-shadow: 0 -3px rgb(53,167,110) inset;transition: 0.2s;" type="submit" value="Отправить" name="main_form_submit"  >
                </div>
            </div>
    </form>
    <table method="post" style="border: 1px solid black; margin-top:30px;margin-left: 30px">
            <tr>
                <td> |  Название объявления </td>
                <td>  |  Цена </td>
                <td>  |  Имя </td>
                <td>  |  Удалить | </td>
            </tr>
        </div>
        <?php show_table_contents();
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
                        'notice_field_is_empty'=> ""
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
if (is_file('./adsholder.txt')){
    
$ads_container= unserialize(file_get_contents('./adsholder.txt'));
  }
  else{
      $ads_container= "";
  }
  $ads_save_checker=$ads_container;
  
   if (isset($_POST['main_form_submit'])) { //блок проверки какая кнопка была нажата. Если Отправить, то передаем записи из поста в сессию 
       if (($_POST['title'])){
           if (isset($_SESSION['save_changes_id'])){ //при нажатой кнопке Отправить если в сессии сохранен ключ редактируемого обьявления, то сохраняем в него вместо создания нового.
               $ads_container[$_SESSION['save_changes_id']]=$_POST; 
               unset($_SESSION['save_changes_id']); //удаляем ключ в сессии, тем самым закрываем редактирование в сессии. Однако если после этого нажать f5 и повторно отправить, то ключа уже нет и создается новое. если делать редирект, то в коде много что менять придется.
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
           'return_private' => "",
           'return_company' =>"",
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
             if ($return_id['private']=='1'){
                 $showform_params['return_private'] = 'checked=""';
             }else{
                 $showform_params['return_company'] = 'checked=""';
             }
             
            $showform_params['return_send_email'] = (isset($return_id['allow_mails'])) ? 'checked=""' : '';//закончили заполнение массива
            /* решил сохранять ключ редактируемого объявления в сессии, но можно создать еще отдельно файл, 
             * подумал что в уже существующем файле с обьявлениями сохранять не очень опитимизированно будет,
             * т.к лишние перезаписи.
             */
            $_SESSION['save_changes_id'] = $_GET['formreturn']; 
   }
   if ($ads_container!==$ads_save_checker){//ведем запись в файл только если что-то было изменено
  file_put_contents('./adsholder.txt', serialize($ads_container));
   }
   //конец логики, вывод
  show_form($showform_params); //теперь выводим на экран форму с параметрами. если они по дефолту, то ничего не возвращается в форму, если они заполнениы, то мы видим заполненную форму.
  ?>