<?php
error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
ini_set('display_errors', 1);
header('Content-type: text/html; charset=utf-8');

function adsSQLSave( $sent_entry){  
    if ( isset( $sent_entry['return_id'] )  &&  is_numeric( $sent_entry['return_id'] ) ){
            mysql_query('UPDATE `ads_container` SET
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
                or die('an error during update query: '.mysql_error());
    }else{
             mysql_query('INSERT INTO `ads_container` (`private`, `seller_name`, `email`, `allow_mails`, `phone`, `location_id`, `category_id`, `title`, `description`, `price`)
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
                or die('an error occured while making insert query'.mysql_error());
    }
}


function adsSQLDelete( $delete_id){
                mysql_query('DELETE FROM `ads_container` WHERE ((`id` = "'.$delete_id.'"))')
                or die('and error duing delete query: '.mysql_error());
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
                   'return_id' => $_GET['formreturn'],
                   'notice_title_is_empty'=> ""
                                );
            $showform_params['return_send_email'] = ($return['allow_mails']) ? 'checked=""' : '';
            
            return $showform_params;
}

function ads_loadfromsql() {
        $ads_container ="";
        $ads_res = mysql_query('SELECT * FROM `ads_container`') or die('Unable to select ads table:'.mysql_error());
        while ($return_row = mysql_fetch_assoc($ads_res)) {
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
        mysql_free_result($ads_res);
        return $ads_container;
}

function cities_load(){
        $dropbox_res = mysql_query('SELECT * FROM `cities`') or die('an error occured while making CITY query: '.mysql_error());//загружаем города в переменную
        while ($db_cities_row = mysql_fetch_assoc($dropbox_res)){
            $cities[$db_cities_row['city_id']] = $db_cities_row['city_name'];
        }
        mysql_free_result($dropbox_res);
        return $cities;
}

function categories_load(){
    $dropbox_res = mysql_query('SELECT * FROM `categories`') or die('an error occured while making category query: '.mysql_error()); //теперь категории
    while ($db_cat_row = mysql_fetch_assoc($dropbox_res) ){
        $categories[$db_cat_row['cat_name']][$db_cat_row['subcat_id']] =$db_cat_row['subcat_name']; //воссоздаем массив categories в том виде, в котором он будет использоваться
    }
    mysql_free_result($dropbox_res);
    return $categories;
}









//создаем подключение
if (!$config_arr = include('./config.php')){
    die('Unable to run configuration file');
}
$ads_db = mysql_connect($config_arr['server_name'],$config_arr['user_name'],$config_arr['password']) 
                        or die('Cannot connect to database.You might want to <a href="./install.php">install</a>it first '.  mysql_error());
mysql_select_db($config_arr['database'],$ads_db) 
                        or die('Unable to select database. You might want to <a href="./install.php">install</a>it first'.mysql_error());
mysql_query('set names utf8')
                        or die('Unable to set names'.mysql_error());

   
//массивы
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
$cities= cities_load();
$categories= categories_load();


   
   //button processing
   if ( isset( $_POST['main_form_submit'] ) ) {    //send button
          if ( $_POST['title'] ){
             adsSQLSave( $_POST );
          }else{
                $showform_params['notice_title_is_empty'] = 'Введите название';
          }
   }elseif ( isset($_GET['delentry'] ) ) {           //delete button
             adsSQLDelete( $_GET['delentry'] );
   }elseif ( isset($_GET['formreturn']) ) {   
         $ads_container = ads_loadfromsql();
         $showform_params = adsReturn( $ads_container, $showform_params, $_GET['formreturn'] );
         
   }
  
   //если еще не загружена база, то загружаем ее.
   if ( !isset( $ads_container ) ){
   $ads_container = ads_loadfromsql();
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

   
$smarty->assign('radios', array('Частное лицо','Компания'));
$smarty->assign('ads_container',$ads_container);
$smarty->assign('cities',$cities);
$smarty->assign('categories',$categories);
$smarty->assign('showform_params', $showform_params);
$smarty->display('L9HW.tpl');
?>