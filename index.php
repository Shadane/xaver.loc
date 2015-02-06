

<?php
/*
 * Следующие задания требуется воспринимать как ТЗ (Техническое задание)
 * p.s. Разработчик, помни! 
 * Лучше уточнить ТЗ перед выполнением у заказчика, если ты что-то не понял, чем сделать, переделать, потерять время, деньги, нервы, репутацию.
 * Не забывай о навыках коммуникации :)
 * 
 * Задание 1
 * - Вы проектируете интернет магазин. Посетитель на вашем сайте создал следующий заказ (цена, количество в заказе и остаток на складе генерируются автоматически):
 */


$ini_string='
[игрушка мягкая мишка белый]
цена = '.  mt_rand(1, 10).';
количество заказано = '.  mt_rand(1, 10).';
осталось на складе = '.  mt_rand(0, 10).';
diskont = diskont'.  mt_rand(0, 2).';
    
[одежда детская куртка синяя синтепон]
цена = '.  mt_rand(1, 10).';
количество заказано = '.  mt_rand(1, 10).';
осталось на складе = '.  mt_rand(0, 10).';
diskont = diskont'.  mt_rand(0, 2).';
    
[игрушка детская велосипед]
цена = '.  mt_rand(1, 10).';
количество заказано = '.  mt_rand(1, 10).';
осталось на складе = '.  mt_rand(0, 10).';
diskont = diskont'.  mt_rand(0, 2).';

';

function enough_stock(){
    global $bd;
    foreach ($bd as $key => $value ) {
    if ($bd[$key]['количество заказано'] <= $bd[$key]['осталось на складе']){
           diskont_switcher($key); 
        
    }
    else{
    $bd[$key]['количество заказано'] = $bd[$key]['количество заказано']-1;
    enough_stock();
    }
}
}

function bd_push($key, $key2, $value) {
    global $bd;
    $bd[$key][$key2]=$value;
}

function total_bd($key){
    global $bd;
    return $bd['игрушка мягкая мишка белый'][$key]+$bd['одежда детская куртка синяя синтепон'][$key]+$bd['игрушка детская велосипед'][$key];
    
}

if(!function_exists('diskont')){ //not sure if i should use function_exists everytime or not
    function diskont($bd_key='', $discounter_percent='0', $return='') {
        global $bd;
        static $total_price_counter_without_special_events=0;
        settype($total_price_counter_without_special_events, 'float');
        if (!$return)
        {
            $total_price_counter_without_special_events += $bd[$bd_key]['цена'] * (100-$discounter_percent)/100 * $bd[$bd_key]['количество заказано'];
//            echo 'PRICE with disount'.$total_price_counter_without_special_events;
        }
        else
        {
            return $total_price_counter_without_special_events;
        }
        
    }
}
function diskont_switcher($key) {
    global $bd;
    global $diskont_var_func;
    if ($key == 'игрушка детская велосипед' && $bd[$key]['количество заказано'] >= 3) {
        $diskont_var_func = 'diskont';
        $diskont_var_func($key, '30');
    } else
        switch ($bd[$key]['diskont']) {
            case 'diskont1':
                $diskont_var_func = 'diskont'; //бессмысленно, наверное стоило сделать три функции diskont1;diskont2;diskont3 и удаленно запускать их
                $diskont_var_func($key, '10');

                break;
            case 'diskont2':
                $diskont_var_func = 'diskont';
                $diskont_var_func($key, '20');

                break;

            default:
                $diskont_var_func = 'diskont';
                $diskont_var_func($key);

                break;
        }
}

$bd=  parse_ini_string($ini_string, true);
$diskont_var_func='';
$enough_stock_counter='';




//$var_func='diskont';
//call_user_func('var_func', 'discont1');
//diskont_switcher('игрушка мягкая мишка белый');
//diskont_switcher('одежда детская куртка синяя синтепон');
//diskont_switcher('игрушка детская велосипед');

//bd_push('Итого', 'Всего наименований заказано', total_bd('количество заказано'));
//bd_push('Итого', 'Общее кол-во на складе', total_bd('осталось на складе'));
enough_stock();
bd_push('Итого', 'Общая сумма заказа(без скидки)', (total_bd('цена')));


if ($bd['игрушка детская велосипед']['количество заказано']>=3){
bd_push('Скидки', 'Поздравляем! При заказе "игрушка детская велосипед" ваша скидка на этот товар составляет', ' 30%');
}bd_push('Итого', 'Общая сумма заказа(Со скидкой)', $diskont_var_func('','','1'));

print_r($bd);
/*
 * 
 * - Вам нужно вывести корзину для покупателя, где указать: 
 * 1) Перечень заказанных товаров, их цену, кол-во и остаток на складе
 * 2) В секции ИТОГО должно быть указано: сколько всего наименовний было заказано, каково общее количество товара, какова общая сумма заказа
 * - Вам нужно сделать секцию "Уведомления", где необходимо извещать покупателя о том, что нужного количества товара не оказалось на складе
 * - Вам нужно сделать секцию "Скидки", где известить покупателя о том, что если он заказал "игрушка детская велосипед" в количестве >=3 штук, то на эту позицию ему 
 * автоматически дается скидка 30% (соответственно цены в корзине пересчитываются тоже автоматически)
 * 3) у каждого товара есть автоматически генерируемый скидочный купон diskont, используйте переменную функцию, чтобы делать скидку на итоговую цену в корзине
 * diskont0 = скидок нет, diskont1 = 10%, diskont2 = 20%
 * 
 * В коде должно быть использовано:
 * - не менее одной функции
 * - не менее одного параметра для функции
 * операторы if, else, switch
 * статические и глобальные переменные в теле функции
 * 

 */

?>

