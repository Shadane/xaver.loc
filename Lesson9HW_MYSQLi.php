<?php
error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
ini_set('display_errors', 1);
header('Content-type: text/html; charset=utf-8');
session_start();
if (!isset($_SESSION['server_name'],$_SESSION['user_name'],$_SESSION['password'],$_SESSION['database'])){// иначе ошибку выдает если заходить напрямую без install.php
   echo ' <a href="./install.php">необходимо ввести данные, кликай по ссылке</a>'; exit;
}


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
//начало функций
function ads_sqload() {
    /* Эта функция с условием, выполняется только один раз, при повторном вызове возвращает false.
     * Сначала она отправляет запрос с сортировкой, затем вытаскивает данные в массив,
     * из которого мы впоследствии заполняем таблицу с обьявами.
     */
    static $already_dled = FALSE;
    global $ads_container,$ads_db,$result;
    if (!$already_dled) {
        userfunc_query('SELECT * FROM `ads_container`'.  sort_ads().'');
        while ($row = $result->fetch_assoc()) {
            $ads_container[$row['id']] = array(
                'private' => $row['private'],
                'seller_name' => $row['seller_name'],
                'email' => $row['email'],
                'allow_mails' => $row['allow_mails'],
                'phone' => $row['phone'],
                'location_id' => $row['location_id'],
                'category_id' => $row['category_id'],
                'title' => $row['title'],
                'description' => $row['description'],
                'price' => $row['price']
            );
        }
        $already_dled = TRUE;
        $result->free();
        $ads_db -> close();
    } else
        return FALSE;
}

function sort_ads(){ //я уже и сам с трудом понимаю что тут, но в целом работает сносно.
    if (isset($_GET['sortby'])){
        $_SESSION['sortby'] = $_GET['sortby'];
    }
    if (!isset($_SESSION['sortby'])){
        $_SESSION['sortby'] = 'id';
        }
      if (!isset($_SESSION['sort'])){  
          $_SESSION['sort']=0;
      }
      if (!isset($_POST['main_form_submit'])&&!isset($_GET['formreturn'])){
        $_SESSION['sort']+=1;
      }
     if (($_SESSION['sort'] % 2)== 0){
    return 'ORDER BY `'.$_SESSION['sortby'].'`';
}else{
    return 'ORDER BY `'.$_SESSION['sortby'].'` DESC';
}
     }

function allow_mails(){
   if (isset($_POST['allow_mails'])){
       return '1';
   } else{
   return '0';}
}


function userfunc_query($query){
    //функция выполняет запрос, используется для всех запросов.
    global $ads_db, $result;
//    $query = $ads_db-> escape_string($query); //не получается это включить, ошибка, явно что-то с переменными внутри запроса, хотелось бы услышать как правильно делать.
     if (!$result = $ads_db->query($query)) {
        die('Error during query[' . $ads_db->error . ']');
    }
}

function city_cat_load($param) {
    //заполняет массив city, затем вызывает сама себя с другим параметром и заполняет categories
    global $cities, $categories, $ads_db,$result;
        switch ($param){
        case 'cities_and_categories':{
                userfunc_query('SELECT * FROM `cities`');
                while ($row = $result->fetch_assoc()) {
                    $cities[$row['city_id']] = $row['city_name'];
                }
                city_cat_load('categories');
                break;
        }
        case 'categories':{
                userfunc_query('SELECT * FROM `categories`');
                while ($row = $result->fetch_assoc()) {
                    $categories[$row['cat_name']][$row['subcat_id']] = $row['subcat_name'];
                }
                $result->free();
                break;
                
        }   
    }
}

//конец функций
$ads_db = new mysqli($_SESSION['server_name'], $_SESSION['user_name'], $_SESSION['password'], $_SESSION['database']);//устанавливаем соденинение
        if ( $ads_db -> connect_errno > 0 ){
             die('Unable to connect'.$ads_db->connect_error());
        }
   if (!$ads_db ->set_charset('utf8')){
       die('Error while applying utf8 charset'.$ads_db->error);
   }

    
    
   city_cat_load('cities_and_categories');//подгружаем наши массивы с городами и категориями

$ads_container="";
$showform_params = array(  //решил его не загружать в бд, так понятнее, что просто переменная обьявляется
                        'return_private' => "0", 
                        'namereturn' => "",
                        'email_return' => "",
                        'return_send_email' => "1",
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
                                            userfunc_query('UPDATE `ads_container` SET
                                            `private` = '.$_POST['private'].',
                                            `seller_name` = "'.$_POST['seller_name'].'",
                                            `email` = "'.$_POST['email'].'",
                                            `allow_mails` = '.allow_mails().',
                                            `phone` = "'.$_POST['phone'].'",
                                            `location_id` = "'.$_POST['location_id'].'",
                                            `category_id` = "'.$_POST['category_id'].'",
                                            `title` = "'.$_POST['title'].'",
                                            `description` = "'.$_POST['description'].'",
                                            `price` = "'.$_POST['price'].'"
                                             WHERE `id` = '.$_SESSION['save_changes_id'].';');
                                            
                        $showform_params['notice_edit_success'] = 'Объявление успешно отредактировано';
                        unset($_SESSION['save_changes_id']); //удаляем ключ в сессии, тем самым закрываем редактирование в сессии. Однако если после этого нажать f5 и повторно отправить, то ключа уже нет и создается новое. если делать редирект, то в коде много что менять.
           } else {
               userfunc_query('INSERT INTO `ads_container` (`private`, `seller_name`, `email`, `allow_mails`, `phone`, `location_id`, `category_id`, `title`, `description`, `price`)
                                      VALUES ("'.$_POST['private'].'","'
                                                .$_POST['seller_name'].'", "'
                                                .$_POST['email'].'",'
                                                .allow_mails().',"'
                                                .$_POST['phone'].'", "'
                                                .$_POST['location_id'].'", "'
                                                .$_POST['category_id'].'","'
                                                .$_POST['title'].'", "'
                                                .$_POST['description'].'", "'
                                                .$_POST['price'].'")');
          
           }
       }else {
           $showform_params['notice_field_is_empty']='Введите название';

       }
   }elseif (isset($_GET['delentry'])) {           //если нажата кнопка удалить
       userfunc_query('DELETE FROM `ads_container` WHERE ((`id` = "'.$_GET['delentry'].'"))');
       if (isset($_SESSION['save_changes_id'])){  //вот тут приходится еще раз проверять и удалять, иначе потыкавшись можно будет найти баг.
               unset($_SESSION['save_changes_id']); 
       }
   }elseif (isset($_GET['formreturn'])) {        //если нажали на название обьявления, то заполняем массив $showform_params
           ads_sqload();//если мы жмем  на название объявления, то нужно загрузить из базы данных таблицу.
            
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
            $showform_params['return_send_email'] = ($return_id['allow_mails']==true) ? 'checked=""' : '';//закончили заполнение массива
            $_SESSION['save_changes_id'] = $_GET['formreturn']; 
   }elseif (isset($_GET['sortby'])){//если мы нажали на любую из сортировок, то удаляем ключ редактируемого объявления.
       if (isset($_SESSION['save_changes_id'])){
           unset($_SESSION['save_changes_id']);
       }
   }

   
ads_sqload();//загружаем таблицу если она еще не была загружена

$smarty->assign('radios',$radios);
$smarty->assign('ads_container',$ads_container);
$smarty->assign('cities',$cities);
$smarty->assign('categories',$categories);
$smarty->assign('showform_params', $showform_params);
$smarty->display('index.tpl');
?>