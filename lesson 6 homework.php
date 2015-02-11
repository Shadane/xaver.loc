<?php
/*Пробую разбить задачу на блоки:
 * 1. Разобраться в структуре html кода, хотя бы поверхностно. Сделать код читаемым. Удалить лишние элементы.
 * 2. Если правильно понял в файле город.пхп описано сворачивание повторяющихся элементов в цикл. Разобраться в этом и вывести на экран подобным образом.
 * 3. Попробовать записать данные в сессию и вывести на экран через принт, посмотреть структуру массива.
 * 4. Вывести сессию таблицей.Узнать что такое td tr table. Попробовать удаление.
 * 5. Понять как вернуть массив в шаблон.
 * 6. Заполнить созданный нами массив данными из сессии, на этот раз выводить на экран уже их(таблицей).
 * 7. Оформить возвращение в шаблон и удаление.
 */
session_start();
//блоки 


function show_main_table(){
?>
<form  method="post">
    <?php
    if (isset($_POST['return_to_form'])) {
        foreach ($_SESSION['ads'] as $key => $values) {
            if ($_POST['return_to_form'] == $_SESSION['ads'][$key]['title']) {
                $return_key = $key;
            }
        }

//        echo '<td><input type="button" value="' . $return_key . '" id="iddd" name="oneone"></td>';  //просто чтобы увидеть возвращает ли
        show_private($_SESSION['ads'][$return_key]['private']);
        show_name($_SESSION['ads'][$return_key]['seller_name']);
        show_email($_SESSION['ads'][$return_key]['email']);
        if (isset($_SESSION['ads'][$return_key]['allow_mails'])) {
            show_send_email('checked=""');
        } else {
            show_send_email();
        }
        show_phone($_SESSION['ads'][$return_key]['phone']);
        show_city_block($_SESSION['ads'][$return_key]['location_id']);
        category_block($_SESSION['ads'][$return_key]['category_id']);
        show_title($_SESSION['ads'][$return_key]['title']);
        show_description($_SESSION['ads'][$return_key]['description']);
        show_price($_SESSION['ads'][$return_key]['price']);
    } else {
        show_private();
        show_name();
        show_email();
        show_send_email();
        show_phone();
        show_city_block();
        category_block();
        show_title();
        show_description();
        show_price();
    }
    ?>
    <div class="form-row-indented form-row-submit b-vas-submit" id="js_additem_form_submit">
        <div class="vas-submit-button pull-left"> 
            <span class="vas-submit-border"></span> 
            <span class="vas-submit-triangle"></span> 
            <input type="submit" value="Отправить" id="form_submit" name="main_form_submit" class="vas-submit-input" > </div>
    </div>
        
        
</form>
 <table method="post">
  <tr>
    <td>№ </td>
    <td>|Название объявления </td>
    <td>|Цена </td>
    <td>|Имя </td>
    <td>|Удалить </td>
    <td><input type="submit" value="session destroy" id="form_submit" name="session_destroy" class="vas-submit-input" >
  </tr>
<? }


function session_destr(){
    
    session_destroy();
    
}
function show_private($return_private='1'){
    ?>
    <div class="form-row-indented"> 
        <label class="form-label-radio">
            <? if ($return_private=='1'){
            echo '<input type="radio" checked="" value="1" name="private">Частное лицо</label><label class="form-label-radio"><input type="radio" value="0" name="private">Компания</label></div>';
            }
            else
            echo '<input type="radio" value="1" name="private">Частное лицо</label><label class="form-label-radio"><input type="radio"checked="" value="0" name="private">Компания</label></div>';
}

function show_name($namereturn=''){
    ?>
        <div class="form-row"> 
        <label for="fld_seller_name" class="form-label">
            <b id="your-name">Ваше имя</b>
        </label>
            <?
        echo '<input type="text" maxlength="40" class="form-input-text" value="'.$namereturn.'" name="seller_name" id="fld_seller_name"></div>';
            
}

function show_email($email_return=''){
    ?>
    <div class="form-row"> 
        <label for="fld_email" class="form-label">Электронная почта</label>
        <?
        echo '<input type="text" class="form-input-text" value="'.$email_return.'" name="email" id="fld_email"></div>';
            
}

function show_send_email($return_send_email=''){
    ?>
<div class="form-row-indented">
        <label class="form-label-checkbox" for="allow_mails"> 
            
      <?='<input type="checkbox" '.$return_send_email.' value="1" name="allow_mails" id="allow_mails" class="form-input-checkbox">'?>
            <span class="form-text-checkbox">Я не хочу получать вопросы по объявлению по e-mail</span> 
        </label> 
    </div>
        <?php
}

function show_phone($phonereturn=''){
    ?>
    <div class="form-row"> 
        <label id="fld_phone_label" for="fld_phone" class="form-label">Номер телефона</label> 
        <?echo '<input type="text" class="form-input-text" value="'.$phonereturn.'" name="phone" id="fld_phone"></div>';
}

function show_city_block($city=''){
     
    $cities=['641780'=>'Новосибирск','641490'=>'Барабинск','641510'=>'Бердск','641600'=>'Искитим','641630'=>'Колывань','641680'=>'Краснообск','641710'=>'Куйбышев','641760'=>'Мошково','641790'=>'Обь','641800'=>'Ордынское','641970'=>'Черепаново'];
    ?>
    <div id="f_location_id" class="form-row form-row-required"> 
        <label for="region" class="form-label">Город</label> 
        <select title="Выберите Ваш город" name="location_id" id="region" class="form-input-select"> 
            <option value="">-- Выберите город --</option>
            <option class="opt-group" disabled="disabled">-- Города --</option>
            <?php // цикл для вывода городов в выпадающем списке
            foreach ($cities as $key=>$value){
                $selected=($key==$city)? 'selected=""' : '';
                echo ' <option data-coords=",,"'.$selected.' value="'.$key.'">'.$value.'</option>';
            }
            ?>
            <option id="select-region" value="0">Выбрать другой...</option>
        </select>
    </div>
    
    <?php
 }
 function category_block($returncategory=''){
    
    $categories= [
    'Транспорт' => ['9' => 'Автомобили с пробегом', '109' => 'Новые автомобили', '14' => 'Мотоциклы и мототехника', '81' => 'Грузовики и спецтехника', '11' => 'Водный транспорт', '10' => 'Запчасти и аксессуары'],
    'Недвижимость' => ['24' => 'Квартиры', '23' => 'Комнаты', '25' => 'Дома, дачи, коттеджи', '26' => 'Земельные участки', '85' => 'Гаражи и машиноместа', '42' => 'Коммерческая недвижимость', '86' => 'Недвижимость за рубежом'],
    'Работа' => ['111' => 'Вакансии (поиск сотрудников)', '112' => 'Резюме (поиск работы)'],
    'Услуги' => ['114' => 'Предложения услуг', '115' => 'Запросы на услуги'],
    'Личные вещи' => ['27' => 'Одежда, обувь, аксессуары', '29' => 'Детская одежда и обувь', '30' => 'Товары для детей и игрушки', '28' => 'Часы и украшения', '88' => 'Красота и здоровье'],
    'Для дома и дачи' => ['21' => 'Бытовая техника', '20' => 'Мебель и интерьер', '87' => 'Посуда и товары для кухни', '82' => 'Продукты питания', '19' => 'Ремонт и строительство', '106' => 'Растения'],
    'Бытовая электроника' => ['32' => 'Аудио и видео', '97' => 'Игры, приставки и программы', '31' => 'Настольные компьютеры', '98' => 'Ноутбуки', '99' => 'Оргтехника и расходники', '96' => 'Планшеты и электронные книги', '84' => 'Телефоны', '101' => 'Товары для компьютера', '105' => 'Фототехника'],
    'Хобби и отдых' => ['33' => 'Билеты и путешествия', '34' => 'Велосипеды', '83' => 'Книги и журналы', '36' => 'Коллекционирование', '38' => 'Музыкальные инструменты', '102' => 'Охота и рыбалка', '39' => 'Спорт и отдых', '103' => 'Знакомства'],
    'Животные' => ['89' => 'Собаки', '90' => 'Кошки', '91' => 'Птицы', '92' => 'Аквариум', '93' => 'Другие животные', '94' => 'Товары для животных'],
    'Для бизнеса' =>['116'=>'Готовый бизнес','40'=>'Оборудование для бизнеса']
    ];
    ?>
    <div class="form-row"> 
        <label for="fld_category_id" class="form-label">Категория</label> 
        <select title="Выберите категорию объявления" name="category_id" id="fld_category_id" class="form-input-select">
            <option value="">-- Выберите категорию --</option>
            <?php
            foreach ($categories as $category=>$subarray){ //цикл для вывода категорий и субкатерогий в выпадающем списке
                echo '<optgroup label="'.$category.'">';
                foreach ($subarray as $key=>$subcatname){
                    $selected=($key==$returncategory)? 'selected=""' : '';
                    echo '<option '.$selected.' value="'.$key.'">'.$subcatname;
                }
            }
            ?>
            
            
        </select> 
    </div>

    <?php
}

function show_title($returntitle=''){
    ?>
     <div id="f_title" class="form-row f_title">
        <label for="fld_title" class="form-label">Название объявления</label> 
      <?  echo '<input type="text" maxlength="50" class="form-input-text-long" value="'.$returntitle.'" name="title" id="fld_title"></div>';
}

function show_description($returndescription=''){
    ?>
     <div class="form-row"> 
        <label for="fld_description" class="form-label" id="js-description-label">Описание объявления</label>
      <?  echo '<textarea maxlength="3000" name="description" placeholder="'.$returndescription.'" id="fld_description" class="form-input-textarea"></textarea></div>';
}

function show_price($returnprice='0'){
    ?>
    <div id="price_rw" class="form-row rl"> <label id="price_lbl" for="fld_price" class="form-label">Цена</label>
      <?  echo '<input type="text" maxlength="9" class="form-input-text-short" value="'.$returnprice.'" name="price" id="fld_price">';
} //конец блоков вывода формы


//function table(){
//    //выставляет обьявления если при вводе данных мы ввели Название обьявления
////    if (isset($_SESSION['ads'])){
// if (isset($_POST['title'],$_SESSION['ads']))
//{
//         
//     foreach ($_SESSION['ads']as $adsnumber=>$adsdata){
//     if ($_POST['title']==$_SESSION['ads'][$adsnumber]['title']&&isset($_POST['main_form_submit']))
//                {
//unset($_POST['main_form_submit']);
//echo '<tr><td>Объявление с таким названием уже существует, введите другое.</td></tr>';
//break;
//
//     }
//     }
//
//$_SESSION['ads'][]=$_POST;     
//print_r($_SESSION);
//
//  foreach ($_SESSION['ads'] as $key=>$array){
//      
//      if ($_SESSION['ads'][$key]['title']&&isset($_SESSION['ads'][$key]['main_form_submit'])){
//          
//      echo '<tr>';
//          echo '<td>'.$key.'</td>';
//          echo '<td><input type="submit" name="return_to_form" value="'.$array['title'].'"></td>';
//          echo '<td>|'.$array['price'].'</td>';
//          echo '<td>|'.$array['seller_name'].'</td>';
//          echo '<td><input type="submit" value="'.$key.'" id="iddd" name="table_row_delete"></td>';
//          echo '</tr>';
//         
//        }else 
//            {
//            unset($_SESSION['ads'][$key]);
//        }
//        }
//}  
////print_r($_SESSION);
//print_r($_POST);
//
//}
function table(){
    
    show_main_table();
    
    
    
    if (isset($_SESSION['ads'])){
      foreach ($_SESSION['ads']as $adsnumber=>$adsdata){
     if ($_POST['title']==$_SESSION['ads'][$adsnumber]['title']&&isset($_POST['main_form_submit']))
                {
unset($_POST['main_form_submit']);
echo '<text>Объявление с таким названием уже существует, введите другое.</text>';
break;
                }
     }
     }   
if(isset($_POST['main_form_submit'])){
    $_SESSION['ads'][]=$_POST;
}
elseif (isset($_POST['table_row_delete'])){
              unset($_SESSION['ads'][$_POST['table_row_delete']]);
              unset($_POST['table_row_delete']);
          }
          elseif (isset($_POST['session_destroy'])){
    session_destr();
    
}
if(!empty($_SESSION)){
    foreach ($_SESSION['ads'] as $key=>$array){
        if ($_SESSION['ads'][$key]['title']&&isset($_SESSION['ads'][$key]['main_form_submit'])){
          
      echo '<tr>';
          echo '<td>'.$key.'</td>';
          echo '<td><input type="submit" name="return_to_form" value="'.$array['title'].'"></td>';
          echo '<td>|'.$array['price'].'</td>';
          echo '<td>|'.$array['seller_name'].'</td>';
          echo '<td><input type="submit" value="'.$key.'" id="iddd" name="table_row_delete"></td>';
          echo '</tr>';
         
        }else 
            {
            unset($_SESSION['ads'][$key]);
            echo '<text>Введите название обьявления</text>';
        }
        }
}


// print_r($_SESSION);
// echo 'дальше пост';
//print_r($_POST);
}

//2)	Всё что пришло из формы записать в $_SESSION как новое объявление.
//3)	Под формой создать вывод всех объявлений, содержащихся в сессии по шаблону:
//Название объявления | Цена | Имя | Удалить
//4)	При нажатии на «название объявления» на экран выводится шаблон объявления как из пункта 1, только в места полей подставляются истинные значения
//5)	При нажатии на «Удалить», объявление удаляется из сессии
?>
  <? //print_r($_SESSION);?>
  <? table();?>
</table> 