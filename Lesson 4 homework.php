

<?php
error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
ini_set('display_errors', 1);

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
   
    

';//если добавить еще позиций, то все работает
function bd_push($key, $key2, $value) {//Эта функция забивает в массив что укажем
    global $bd;
    $bd[$key][$key2] = $value;
}

function diskont_switcher($key) {//Запускается циклом на 89 строке.  эта функция работает со скидками и в зависимости от значений отправляет данные в функцию diskont
    global $bd;
    global $diskont_var_func; //создано исключительно для переменной функции, правда особого смысла не вижу :(.
    if ($key == 'игрушка детская велосипед' && $bd[$key]['количество заказано'] >= 3 && $bd[$key]['осталось на складе'] >= 3) {//добавил еще такое условие, иначе несостыковка - скидка 30%, а товара нет)
        $diskont_var_func = 'diskont';
        $diskont_var_func($key, '30');
        bd_push('Скидки', 'При заказе "игрушка детская велосипед" в количестве 3 и более и при наличии их на складе ваша скидка составляет', ' 30%');
    } else
        switch ($bd[$key]['diskont']) {
            case 'diskont1':
                $diskont_var_func = 'diskont'; //бессмысленное испольpование переменной функции, но более полезного не придумалось, возможно лучше было написать три функции.
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

if (!function_exists('diskont')) { //эта функция ведет почти все рассчеты.Кстати не знаю стоит ли каждый раз писать вот так if !function exists или не нужно.

    function diskont($bd_key = '', $discounter_percent = '0', $return = '') {  //ключ и проценты получает из предыдущей функции, 
        global $bd;                                                      //третий параметр вызывается при добавлении в массив Итоговой суммы, он возвращает значение статической переменной,
        static $total_price_counter_without_special_events = 0;            //в которой ведутся все подсчеты
        settype($total_price_counter_without_special_events, 'float');
        if (!$return) {                                                     //если третьего параметра не задано, то 
            if ($bd[$bd_key]['количество заказано'] <= $bd[$bd_key]['осталось на складе'])
            { //если заказано <= остаток на складе, то производим вычисления(с учетом скидок)
                $total_price_counter_without_special_events += $bd[$bd_key]['цена'] * (100 - $discounter_percent) / 100 * $bd[$bd_key]['количество заказано'];
            } 
            else 
            { //если же заказано больше чем есть, то считаем исходя из того сколько есть на складе и выдаем уведомление об этом, записываем его в массив функцией bd_push(первая)
                $total_price_counter_without_special_events += $bd[$bd_key]['цена'] * (100 - $discounter_percent) / 100 * $bd[$bd_key]['осталось на складе'];
                bd_push('Уведомления', 'Внимание, позиций "' . $bd_key . '" недостаточно для совершения покупки.', 'Конечная цена пересчитана по остатку на складе ( ' . $bd[$bd_key]['осталось на складе'] . ' ).');
            }
        } else {
            return $total_price_counter_without_special_events;
        }
    }

}

$bd = parse_ini_string($ini_string, true);
$diskont_var_func = ''; //снова повторюсь, что эта переменная исключительно для переменной функции
$key_quantity=0;
$total_orders=0;
foreach ($bd as $key => $value) { //цикл, который запускает обработку скидок для каждого наименования
    diskont_switcher($key);
    $total_orders+=$bd[$key]['количество заказано'];
    $key_quantity+=1;//тут можно поставить условие, что позиции добавляются только если заказов не 0, но у нас рандом от 1.
}

//блок записи в массив 
bd_push('Итого', 'Наименований заказано:', $key_quantity);
bd_push('Итого', 'Общее количество заказанного товара:', $total_orders); //добавляет значения в массив
bd_push('Итого', 'Итоговая сумма заказа:', $diskont_var_func('', '', '1'));

//цикл, который выводит массив на экран.
echo 'Корзина покупок:'.'<br/>'; 
foreach ($bd as $brand => $massiv) { 
    echo '<br/>';
    echo '<br/>'.$brand.'<br/>';
    foreach ($massiv as $inner_key => $value) {
        if ($brand == 'Уведомления'||$brand == 'Итого'||$brand == 'Скидки'){
            echo '' . $inner_key . ' ' . $value . '<br/> ';
        }
        
        elseif ($inner_key !== 'diskont') {//чтобы при выводе не показывалась графа diskont
            echo '[' . $inner_key . '] = ' . $value . '; ';
        } 
        }
    }

?>

