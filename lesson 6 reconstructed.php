<?php
/* Ход работы.
 * Пробую разбить задачу на блоки:
 * 1. Разобраться в структуре html кода, хотя бы поверхностно. Сделать код читаемым. Удалить лишние элементы.
 * 2. Если правильно понял в файле город.пхп описано сворачивание повторяющихся элементов в цикл. Разобраться в этом и вывести на экран подобным образом.
 * 3. Попробовать записать данные в сессию и вывести на экран через принт, посмотреть структуру массива.
 * 4. Вывести сессию таблицей.Узнать что такое td tr table. Попробовать удаление.//в этом месте с удалением все пошло через Ж, т.к я не додумался до a href и начал делать по кнопке.
 * 5. Понять как вернуть массив в шаблон.
 * 7. Оформить возвращение в шаблон и удаление.
 * 8. В свободное время разобраться в том как сделать форму красивой.
 */

//блоки
function show_table(){ //блок вывода таблицы по шаблону 
    ?>                              
                                    <table method="post" style="border: 1px solid black; margin-top:30px;margin-left: 50px">
                                        <div >
                                        <tr>
                                            <td> |  Название объявления </td>
                                            <td>  |  Цена </td>
                                            <td>  |  Имя </td>
                                            <td>  |  Удалить | </td>
                                        </tr>
                                        </div>
    <?
/*если сессия не пустая, то перебираем значения подмассива ['ads'] в сессии, 
 * если они содержат что-то в строке title, то выводим на экран обьявление. 
 * Если нет, то выводим сообщение "Введите название обьявления", соответственно
 * обявление не будет показано, а будет удалено из сессии. Не знаю стоит ли
 * напрямую перебирать сессию или создать массив и выгружать в него ['ads']?
 */
    if (!empty($_SESSION)) {
        foreach ($_SESSION['ads'] as $key => $array) {
            if ($_SESSION['ads'][$key]['title']) {
             
                echo '<tr>';
                echo '<td> |  <a href="?formreturn=' . $key . '"> ' . $array["title"] . '</td>';
                echo '<td>  |  ' . $array['price'] . '</td>';
                echo '<td>  |  ' . $array['seller_name'] . '</td>';
                echo '<td>  |  <a href="?delentry=' . $key . '">Удалить</a> |</td>';
                echo '</tr>';
              
            } else {
                unset($_SESSION['ads'][$key]);
                echo '<text style="margin-left:160px;">Введите название обьявления</text>';
            }
        }
    }
    ?>
                                      </table>
    <?
}

function show_form($showform_params) { 
/*очень много, духу не хватит все комментить. справа код хтмл, слева пхп.
 * все открытия и закрытия <??> тегов в этом блоке расположены слева.
 */
?>
                                            <form  method="post">
                                                <div style="position: static; margin-left:220px;margin-top:10px"> 
                                                    <label>
<?
    if ($showform_params['return_private'] == '1') {
        echo '<input type="radio" checked="" value="1" name="private">Частное лицо</label><label style="margin-left:20px"><input type="radio" value="0" name="private">Компания</label></div>';
    } else {
        echo '<input type="radio" value="1" name="private">Частное лицо</label><label style="margin-left:20px"><input type="radio"checked="" value="0" name="private">Компания</label></div>';
            }
?>
                                                 <div style="margin-left:60px;margin-top:10px"> 
                                                    <label>
                                                           <b>Ваше имя</b>
                                                      </label>
<?
    echo '<input style="margin-left:85px; width:230px" type="text" maxlength="40" value="' . $showform_params["namereturn"] . '" name="seller_name"></div>';
?>
                                                 <div style="margin-left:60px;  margin-top:10px"> 
                                                     <label>Электронная почта</label>
<?
    echo '<input style="margin-left:27px; width:230px;" type="text"  value="' . $showform_params["email_return"] . '" name="email" id="fld_email"></div>';
?>
                                                   <div style="margin-left:217px;  margin-top:10px">
                                                      <label> 

<?= '<input type="checkbox" ' . $showform_params["return_send_email"] . ' value="1" name="allow_mails" id="allow_mails" class="form-input-checkbox">' 
?>
                                                     Я не хочу получать вопросы по объявлению по e-mail
                                                          </label> 
                                                             </div>
                                                     
                                                     <div style="margin-left:60px;  margin-top:10px"> 
                                                          <label>Номер телефона</label> 
<?
    echo '<input style="margin-left:46px; width:230px" type="text"  value="' . $showform_params["phonereturn"] . '" name="phone"></div>';

    $cities = ['641780' => 'Новосибирск', '641490' => 'Барабинск', '641510' => 'Бердск', '641600' => 'Искитим', '641630' => 'Колывань', '641680' => 'Краснообск', '641710' => 'Куйбышев', '641760' => 'Мошково', '641790' => 'Обь', '641800' => 'Ордынское', '641970' => 'Черепаново'];
?>
                                                       <div style="margin-left:60px;  margin-top:10px"> 
                                                           <label >Город</label> 
                                                            <select style="margin-left:118px; width:230px;height:22px" title="Выберите Ваш город" name="location_id"> 
                                                              <option>-- Выберите город --</option>
                                                                 <option disabled="disabled">-- Города --</option>
<?
    // цикл для вывода городов в выпадающем списке
    foreach ($cities as $key => $value) {
        $selected = ($key == $showform_params["city"]) ? 'selected=""' : '';
        echo ' <option data-coords=",,"' . $selected . ' value="' . $key . '">' . $value . '</option>';
    }
?>
                                                        <option>Выбрать другой...</option>
                                                             </select>
                                                                  </div>

<?
    $categories = [
        'Транспорт' => ['9' => 'Автомобили с пробегом', '109' => 'Новые автомобили', '14' => 'Мотоциклы и мототехника', '81' => 'Грузовики и спецтехника', '11' => 'Водный транспорт', '10' => 'Запчасти и аксессуары'],
        'Недвижимость' => ['24' => 'Квартиры', '23' => 'Комнаты', '25' => 'Дома, дачи, коттеджи', '26' => 'Земельные участки', '85' => 'Гаражи и машиноместа', '42' => 'Коммерческая недвижимость', '86' => 'Недвижимость за рубежом'],
        'Работа' => ['111' => 'Вакансии (поиск сотрудников)', '112' => 'Резюме (поиск работы)'],
        'Услуги' => ['114' => 'Предложения услуг', '115' => 'Запросы на услуги'],
        'Личные вещи' => ['27' => 'Одежда, обувь, аксессуары', '29' => 'Детская одежда и обувь', '30' => 'Товары для детей и игрушки', '28' => 'Часы и украшения', '88' => 'Красота и здоровье'],
        'Для дома и дачи' => ['21' => 'Бытовая техника', '20' => 'Мебель и интерьер', '87' => 'Посуда и товары для кухни', '82' => 'Продукты питания', '19' => 'Ремонт и строительство', '106' => 'Растения'],
        'Бытовая электроника' => ['32' => 'Аудио и видео', '97' => 'Игры, приставки и программы', '31' => 'Настольные компьютеры', '98' => 'Ноутбуки', '99' => 'Оргтехника и расходники', '96' => 'Планшеты и электронные книги', '84' => 'Телефоны', '101' => 'Товары для компьютера', '105' => 'Фототехника'],
        'Хобби и отдых' => ['33' => 'Билеты и путешествия', '34' => 'Велосипеды', '83' => 'Книги и журналы', '36' => 'Коллекционирование', '38' => 'Музыкальные инструменты', '102' => 'Охота и рыбалка', '39' => 'Спорт и отдых', '103' => 'Знакомства'],
        'Животные' => ['89' => 'Собаки', '90' => 'Кошки', '91' => 'Птицы', '92' => 'Аквариум', '93' => 'Другие животные', '94' => 'Товары для животных'],
        'Для бизнеса' => ['116' => 'Готовый бизнес', '40' => 'Оборудование для бизнеса']
                  ];
?>
                                                            <div style="margin-left:60px;  margin-top:10px"> 
                                                                <label for="fld_category_id" class="form-label">Категория</label> 
                                                                <select style="margin-left:89px; width:230px;height:22px" name="category_id">
                                                                    <option value="">-- Выберите категорию --</option>
<?
    foreach ($categories as $category => $subarray) { //цикл для вывода категорий и субкатерогий в выпадающем списке
        echo '<optgroup label="' . $category . '">';
        foreach ($subarray as $key => $subcatname) {
            $selected = ($key == $showform_params["returncategory"]) ? 'selected=""' : '';
            echo '<option ' . $selected . ' value="' . $key . '">' . $subcatname;
        }
    }
?>
                                                                </select> 
                                                            </div>
                                                            <div style="margin-left:60px;  margin-top:10px">
                                                                <label>Название объявления</label> 
                                                                
<?='<input style="margin-left:12px; width:230px;" type="text" maxlength="50" placeholder="обязательное поле" value="' . $showform_params["returntitle"] . '" name="title"></div>';
?>
                                                            <div style="margin-left:60px;  margin-top:10px"> 
                                                               <label style="position:absolute">Описание объявления</label>
                                                               
<?='<textarea style="margin-left:162px; width:230px;height:70px;" maxlength="3000" name="description" >' . $showform_params["returndescription"] . '</textarea></div>';
?>
                                                               <div style="margin-left:60px;  margin-top:10px"> 
                                                                   <label >Цена</label>

<?='<input style="margin-left:124px; width:230px" type="text" maxlength="9"  value="' . $showform_params["returnprice"] . '" name="price" >';
?>
                                                                   
                                                            <div>
                                                                <div style="margin-left:161px;  margin-top:10px"> 
                                                                   
                                                                    <input style="height:30px" type="submit" value="Отправить" name="main_form_submit"  > </div>
                                                            </div>
                                                          </form>

                                   
                                      
<?
}
//конец блоков 
  session_start();
    $showform_params = ['return_private' => "1", //эта переменная передается в форму на строке 204, если она заполнена данными, то они выведутся в нашей форме
                        'namereturn' => "",
                        'email_return' => "",
                        'return_send_email' => "",
                        'phonereturn' => "",
                        'city' => "",
                        'returncategory' => "",
                        'returntitle' => "",
                        'returndescription' => "",
                        'returnprice' => "0"
                        ];
  
   if (isset($_POST['main_form_submit'])) { //блок проверки какая кнопка была нажата. Если Отправить, то передаем записи из поста в сессию
       $_SESSION['ads'][] = $_POST;
   }elseif (isset($_GET['delentry'])) {           //если нажата кнопка удалить
       unset($_SESSION['ads'][$_GET['delentry']]);
   }elseif (isset($_GET['formreturn'])) {        //если нажали на название обьявления, то заполняем массив $showform_params , если не нажали, то на 164 строке заполнено по дефолту.
       $showform_params = ['return_private' => $_SESSION['ads'][$_GET['formreturn']]['private'],
           'namereturn' => $_SESSION['ads'][$_GET['formreturn']]['seller_name'],
           'email_return' => $_SESSION['ads'][$_GET['formreturn']]['email'],
           'return_send_email' => "",
           'phonereturn' => $_SESSION['ads'][$_GET['formreturn']]['phone'],
           'city' => $_SESSION['ads'][$_GET['formreturn']]['location_id'],
           'returncategory' => $_SESSION['ads'][$_GET['formreturn']]['category_id'],
           'returntitle' => $_SESSION['ads'][$_GET['formreturn']]['title'],
           'returndescription' => $_SESSION['ads'][$_GET['formreturn']]['description'],
           'returnprice' => $_SESSION['ads'][$_GET['formreturn']]['price']];
       $showform_params['return_send_email'] = (isset($_SESSION['ads'][$_GET['formreturn']]['allow_mails'])) ? 'checked=""' : ''; //закончили заполнение массива
   }
  show_form($showform_params); //теперь выводим на экран форму с параметрами. если они по дефолту, то ничего не возвращается в форму, если они заполнениы, то мы видим заполненную форму.
  show_table(); // выводим таблицу название-цена-имя-удалить.
  ?>