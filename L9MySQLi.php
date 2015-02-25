<?php
error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
ini_set('display_errors', 1);
header('Content-type: text/html; charset=utf-8');
//начало функций
function adsSQLSave( $sent_entry, $ads_db){  
    if ( isset( $sent_entry['return_id'] )  &&  is_numeric( $sent_entry['return_id'] ) ){
        userfunc_query( $ads_db, 'UPDATE `ads_container` SET
                                            `private` = '.$sent_entry['private'].',
                                            `seller_name` = "'.$sent_entry['seller_name'].'",
                                            `email` = "'.$sent_entry['email'].'",
                                            `allow_mails` = "'.isset($sent_entry['allow_mails']).'",
                                            `phone` = "'.$sent_entry['phone'].'",
                                            `location_id` = "'.$sent_entry['location_id'].'",
                                            `category_id` = "'.$sent_entry['category_id'].'",
                                            `title` = "'.$sent_entry['title'].'",
                                            `description` = "'.$sent_entry['description'].'",
                                            `price` = "'.$sent_entry['price'].'"
                                             WHERE `id` = '.$sent_entry['return_id'].';') 
                or die('an error during update query: '.  $ads_db->error);
    }else{
        userfunc_query( $ads_db, 'INSERT INTO `ads_container` (`private`, `seller_name`, `email`, `allow_mails`, `phone`, `location_id`, `category_id`, `title`, `description`, `price`)
                                      VALUES ("'.$sent_entry['private'].'","'
                                                .$sent_entry['seller_name'].'", "'
                                                .$sent_entry['email'].'","'
                                                .isset($sent_entry['allow_mails']).'","'
                                                .$sent_entry['phone'].'", "'
                                                .$sent_entry['location_id'].'", "'
                                                .$sent_entry['category_id'].'","'
                                                .$sent_entry['title'].'", "'
                                                .$sent_entry['description'].'", "'
                                                .$sent_entry['price'].'")')
                or die('an error occured while making insert query'.$ads_db->error);
    }
}


function adsSQLDelete( $delete_id, $ads_db){
             userfunc_query($ads_db, 'DELETE FROM `ads_container` WHERE ((`id` = "'.$delete_id.'"))')
                    or die('and error duing delete query: '.$ads_db->error);
}


function adsReturn( $ads_container, $showform_params, $return_id ){
            $return = $ads_container[$return_id];
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
                   'notice_title_is_empty'=> ""
                                );
            $showform_params['return_send_email'] = ($return['allow_mails']) ? 'checked=""' : '';
            
            return $showform_params;
}

function ads_loadfromsql( $ads_db ) {
        $ads_container ="";
        $ads_res = userfunc_query( $ads_db, 'SELECT * FROM `ads_container`');
        while ($return_row = $ads_res->fetch_assoc()) {
            $ads_container[$return_row['id']] = array(
                                                        'private' => $return_row['private'],
                                                        'seller_name' => $return_row['seller_name'],
                                                        'email' => $return_row['email'],
                                                        'allow_mails' => $return_row['allow_mails'],
                                                        'phone' => $return_row['phone'],
                                                        'location_id' => $return_row['location_id'],
                                                        'category_id' => $return_row['category_id'],
                                                        'title' => $return_row['title'],
                                                        'description' => $return_row['description'],
                                                        'price' => $return_row['price']
                                                     );
        }
        
        
        return $ads_container;
        $ads_res->free();
        $ads_db->close();
}



    function userfunc_query( $ads_db ,$query){
        if (!$result = $ads_db->query($query)) {
              die('Error during query[' . $ads_db->error . ']');
        }
        return $result;
        $result->free();
    }



     function cities_load($ads_db){
                $result = userfunc_query($ads_db, 'SELECT * FROM `cities`');
                while ($row = $result->fetch_assoc()) {
                    $cities[$row['city_id']] = $row['city_name'];
                }
                return $cities;
                $result->free();
     }   
     
     function categories_load($ads_db){
                $result = userfunc_query($ads_db, 'SELECT * FROM `categories`');
                while ($row = $result->fetch_assoc()) {
                    $categories[$row['cat_name']][$row['subcat_id']] = $row['subcat_name'];
                }
                return $categories;
                $result->free();
     }


//конец функций
if (!$config_arr = include('./config.php')){
        die('Unable to run configuration file <a href="./install.php">install it </a>');
}
$ads_db = new mysqli($config_arr['server_name'],$config_arr['user_name'],$config_arr['password'],$config_arr['database']);//устанавливаем соденинение
        if ( $ads_db -> connect_errno > 0 ){
             die('Unable to connect'.$ads_db->connect_error());
        }
if (!$ads_db ->set_charset('utf8')){
       die('Error while applying utf8 charset:  '.$ads_db->error);
}

    
    
$cities = cities_load($ads_db);
$categories = categories_load($ads_db);

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
  
   if ( isset( $_POST['main_form_submit'] ) ) {    //send button
          if ( $_POST['title'] ){
             adsSQLSave( $_POST, $ads_db );
          }else{
                $showform_params['notice_title_is_empty'] = 'Введите название';
          }
   }elseif ( isset($_GET['delentry'] ) ) {           //delete button
             adsSQLDelete( $_GET['delentry'] ,$ads_db );
   }elseif ( isset($_GET['formreturn']) ) {   
         $ads_container = ads_loadfromsql( $ads_db );
         $showform_params = adsReturn( $ads_container, $showform_params, $_GET['formreturn'] );
         
   }
  
   //если еще не загружена база, то загружаем ее.
   if ( !isset( $ads_container ) ){
   $ads_container = ads_loadfromsql( $ads_db );
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


$smarty->assign('radios',$radios=array('Частное лицо','Компания'));
$smarty->assign('ads_container',$ads_container);
$smarty->assign('cities',$cities);
$smarty->assign('categories',$categories);
$smarty->assign('showform_params', $showform_params);
$smarty->display('L9MySQLi.tpl');
?>