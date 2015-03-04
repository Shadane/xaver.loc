<?php
error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
ini_set('display_errors', 1);
header('Content-type: text/html; charset=utf-8');

function config(){
    include('./config.php');
    if (!isset($config_arr)){
        die ('No userdata, try checking config.php');
    }
    return $config_arr;
}

function dbconnect($config_arr){
        $ads_db = mysql_connect($config_arr['server_name'],$config_arr['user_name'],$config_arr['password']) 
                        or die('Cannot connect to database.You might want to <a href="./install.php">install</a>it first '.  mysql_error());
        mysql_select_db($config_arr['database'],$ads_db) 
                        or die('Unable to select database. You might want to <a href="./install.php">install</a>it first'.mysql_error());
        mysql_query('set names utf8')
                        or die('Unable to set names'.mysql_error());
return $ads_db;
}

function adsSQLSave( $sent_entry){  
     foreach ($sent_entry as $key => $value){ 
        $sent_entry[$key] = mysql_real_escape_string($sent_entry[$key]);
     }
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


function adsReturn( $showform_params, $return_id ){
    
        $cols=array( 'id', 'private', 'seller_name', 'email', 'allow_mails', 'phone', 'location_id', 'category_id', 'title', 'description', 'price' );
        $query = 'SELECT '.implode (',', $cols).' FROM `ads_container` WHERE id = '.$return_id;
        $ads_container= adsLoad($query, $cols);
        if (!$ads_container){
           return $showform_params;
        }
            
    
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

function adsLoad($query, $cols) {
        $ads_container ="";
        $ads_res = mysql_query($query) or die('Unable to load ads from database'.mysql_error());
        while ($return_row = mysql_fetch_assoc($ads_res)) {
            foreach ($cols as $value){
                $ads_container[$return_row['id']][$value]=$return_row[$value];
            }
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

$config_arr = config();
$ads_db = dbconnect($config_arr);

   
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
  }elseif ( isset($_GET['delentry']) && is_numeric($_GET['delentry']) ) {           //delete button
             adsSQLDelete( $_GET['delentry'] );
  }elseif ( isset($_GET['formreturn'] ) && is_numeric($_GET['formreturn'] )) {
             $showform_params = adsReturn( $showform_params, $_GET['formreturn'] );
         
   }
   
   
   //loading ads
   $cols=array( 'id', 'seller_name', 'title', 'price' );
   $query = 'SELECT '.implode (',', $cols).' FROM `ads_container`';
   $ads_container = adsLoad($query,$cols);
   
   
   
//smarty block+display
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
$smarty->display('L9.tpl');
?>