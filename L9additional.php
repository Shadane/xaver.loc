<?php
error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
ini_set('display_errors', 1);
header('Content-type: text/html; charset=utf8');
//начало функций
function config(){
    include('./config.php');
    if (!isset($config_arr)){
        die ('No userdata, try checking config.php');
    }
    return $config_arr;
}

function dbconnect($config_arr){
$ads_db = new mysqli($config_arr['server_name'],$config_arr['user_name'],$config_arr['password'],$config_arr['database']);//устанавливаем соденинение
        if ( $ads_db -> connect_errno > 0 ){
             die('Unable to connect'.$ads_db->connect_error().'<a href="./install.php">You might want to install it first </a>');
        }
if (!$ads_db ->set_charset("utf8")){
       die('Error while applying utf8 charset:  '.$ads_db->error);
}
return $ads_db;
}

function author_controller($ads_db, $sent_entry){
     if ( $sent_entry['email'] && $sent_entry['seller_name']){ //если заполнены поля мыло и имя
                     $result=userfunc_query($ads_db, 'SELECT `id` from `ads_authors` WHERE `email`= "'.$sent_entry['email'].'"'); //то делаем выборку по мылу
                     if ($result->num_rows > 0){ //если такое мыло уже есть,
                         userfunc_query($ads_db, 'UPDATE `ads_authors` SET `seller_name`="'.$sent_entry['seller_name'].'"WHERE `email`= "'.$sent_entry['email'].'"' );  //прописываем новое имя владельцу мыла.
                                $author_id = $result->fetch_assoc();  // выгружаем из него id автора, которое понадобится для добавления или редактирования объявления
                                $result->free();
                     }else{         //если же такого мыла нет, то создаем новую запись в базе данных и выгружаем id добавленного автора
                                userfunc_query( $ads_db, 'INSERT INTO `ads_authors` (`seller_name`, `email`)
                                          VALUES ("'.$sent_entry['seller_name'].'", "'.$sent_entry['email'].'" )');
                                $author_id['id'] = $ads_db->insert_id;
                     }
     }else { //если же мыло и имя не заполнены в форме
                $author_id['id'] = $_POST['saved_email']; //то возвращаем значение сохраненных записей мыло\имя
     }
            return $author_id['id'];
}

function escape_2dim_arr($ads_db, $sent_entry){
      foreach ($sent_entry as $key => $value){ 
        $sent_entry[$key] = $ads_db->real_escape_string($sent_entry[$key]); //+ в шаблоне сделал вывод с |escape:'htmlall'
       }
       return $sent_entry;
}
function adsSQLSave( $sent_entry, $ads_db){ 

    if ( isset( $sent_entry['return_id'] )  &&  is_numeric( $sent_entry['return_id'] ) ){
                userfunc_query( $ads_db, 'UPDATE `ads_container` SET `private` = "'.$sent_entry['private'].'", '                                                                     
                                                                      . '`allow_mails` = "'.isset($sent_entry['allow_mails']).'", '
                                                                      . '`phone` = "'.$sent_entry['phone'].'", '
                                                                      . '`location_id` = "'.$sent_entry['location_id'].'", '
                                                                      . '`category_id` = "'.$sent_entry['category_id'].'", '
                                                                      . '`title` = "'.$sent_entry['title'].'", '
                                                                      . '`description` = "'.$sent_entry['description'].'", '
                                                                      . '`price` = "'.$sent_entry['price'].'" ,'
                                                                      . '`author_id` = "'.$sent_entry['author_id'].'" '
                                                                      . 'WHERE `id` = "'.$sent_entry['return_id'].'"') ;                               
    }else{   
        userfunc_query( $ads_db, 'INSERT INTO `ads_container` (`private`, `allow_mails`, `phone`, `location_id`, `category_id`, `title`, `description`, `price`, `author_id`)
                                      VALUES ("'.$sent_entry['private'].'","'
                                                .isset($sent_entry['allow_mails']).'","'
                                                .$sent_entry['phone'].'", "'
                                                .$sent_entry['location_id'].'", "'
                                                .$sent_entry['category_id'].'","'
                                                .$sent_entry['title'].'", "'
                                                .$sent_entry['description'].'", "'
                                                .$sent_entry['price'].'","'
                                                .$sent_entry['author_id'].'")');
    }
}


function adsSQLDelete( $delete_id, $ads_db){
             userfunc_query($ads_db, 'DELETE FROM `ads_container` WHERE ((`id` = "'.$delete_id.'"))')
                    or die('and error duing delete query: '.$ads_db->error);
}


function adsReturn( $ads_db, $showform_params, $return_id ){
         $cols=array( 'id', 'private', 'allow_mails', 'phone', 'location_id', 'category_id', 'title', 'description', 'price','seller_name', 'email' );
         $query = 'SELECT ads.id, ads.private, ads.allow_mails,ads.phone, ads.location_id, ads.category_id, ads.title, ads.description, ads.price,auth.seller_name, auth.email FROM `ads_container` as `ads` INNER JOIN `ads_authors` as `auth` on ads.author_id=auth.id WHERE ads.id = '.$return_id;//теперь без implode
         $ad_toreturn = adsLoad( $ads_db , $query , $cols );
         
         if (!$ad_toreturn){
             return $showform_params;
         }
            $return = $ad_toreturn[$return_id];
            $showform_params = array(
                   'return_private' => $return['private'],
                   'namereturn' => $return['seller_name'],
                   'email_return' => $return['email'],
                   'phonereturn' => $return['phone'],
                   'city' => $return['location_id'],
                   'returncategory' => $return['category_id'],
                   'returntitle' =>$return['title'],
                   'returndescription' => $return['description'],
                   'returnprice' => $return['price'],
                   'return_id' => $return_id,
                   'notice_title_is_empty'=> "",
                                );
            $showform_params['return_send_email'] = ($return['allow_mails']) ? 'checked=""' : '';
            
            return $showform_params;
}

function adsLoad( $ads_db, $query , $cols) { 
        $ads_container ="";
        $ads_res = userfunc_query( $ads_db, $query );
        while ($return_row = $ads_res->fetch_assoc()) {
            foreach ($cols as $value){
                $ads_container[$return_row['id']][$value]=$return_row[$value];
            }
        }
        return $ads_container;
        
        
}



    function userfunc_query( $ads_db ,$query){
        if (!$result = $ads_db->query($query)) {
              die('Error during query[' . $ads_db->error . ']');
        }
        return $result;
    }



     function cities_load($ads_db){
                $result = userfunc_query($ads_db, 'SELECT * FROM `cities`');
                while ($row = $result->fetch_assoc()) {
                    $cities[$row['city_id']] = $row['city_name'];
                }
                return $cities;

     }   
     
     function categories_load($ads_db){
                $result = userfunc_query($ads_db, 'SELECT * FROM `categories`');
                while ($row = $result->fetch_assoc()) {
                    $categories[$row['cat_name']][$row['subcat_id']] = $row['subcat_name'];
                }
                return $categories;
     }

     function emails_load($ads_db){
         $emails='';
         $result= userfunc_query($ads_db, 'SELECT id, seller_name, email FROM `ads_authors`');
         while ($row = $result->fetch_assoc()) {
             $emails[$row['id']] = 'Почта:&nbsp; '. $row['email'].'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Имя:&nbsp;  '.$row['seller_name'];
         }

         return $emails;
     }

//connection
$config_arr = config();
$ads_db = dbconnect($config_arr);

    
//filling arrays  

$categories = categories_load($ads_db);

$cities = cities_load($ads_db);
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
                        'notice_field_is_empty'=> "",
                        'return_id' => ""
                         );
//button controller
   if ( isset( $_POST['main_form_submit'] ) ) {    //send button
          if ( $_POST['title']&&(($_POST['seller_name']&&$_POST['email'])||($_POST['saved_email'])) ){//если есть (название и (имя+мыло или указано сохраненное)
              $sent_entry = escape_2dim_arr($ads_db, $_POST);
              $_POST['author_id'] = author_controller($ads_db,$_POST);
              adsSQLSave( $_POST, $ads_db );
          }else{
                $showform_params['notice_title_is_empty'] = 'You must fill Name, Email and Title(or choose email+name from selector) fields to proceed ';
          }
   }elseif ( isset($_GET['delentry']) && is_numeric($_GET['delentry']) ) {           //delete button
             adsSQLDelete( $_GET['delentry'] ,$ads_db);
   }elseif ( isset($_GET['formreturn'] ) && is_numeric($_GET['formreturn'] )) {   //достаточно ли is_numeric для предотвращения инъекций? или нужно прогнать еще через intval? Или лучше привести тип к int?
            $showform_params = adsReturn( $ads_db, $showform_params, $_GET['formreturn'] );
   }

   
//loading all ads for table
$cols= array( 'id', 'seller_name', 'title', 'price' );
$query = 'SELECT ads.id, ads.title, ads.price, auth.seller_name FROM `ads_container`as `ads` INNER JOIN `ads_authors` as `auth` on ads.author_id=auth.id ORDER by ads.id';
$ads_container = adsLoad( $ads_db , $query , $cols );
$emails= emails_load($ads_db);//пришлось переместить сюда из блока загрузки массивов, т.к во время button controller могут произойти изменения в пользователях.
//closing connection
$ads_db->close();

//smarty assigns, display
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


$smarty->assign('radios',$radios=array('Частное лицо','Компания'));
$smarty->assign('ads_container',$ads_container);
$smarty->assign('cities',$cities);
$smarty->assign('emails',$emails);
$smarty->assign('categories',$categories);
$smarty->assign('showform_params', $showform_params);
$smarty->display('L9.tpl');
?>