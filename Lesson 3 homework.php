<?php

error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
ini_set('display_errors', 1);
/*
адание 1
 * - Создайте массив $date с пятью элементами
 * - C помощью генератора случайных чисел забейте массив $date юниксовыми метками
 * - Сделайте вывод сообщения на экран о том, какой день в сгенерированном массиве получился наименьшим, а какой месяц наибольшим
 * - Отсортируйте массив по возрастанию дат
 * - С помощью функция для работы с массивами извлеките последний элемент массива в новую переменную $selected
 * - C помощью функции date() выведите $selected на экран в формате "дд.мм.ГГ ЧЧ:ММ:СС"
 * - Выставьте часовой пояс для Нью-Йорка, и сделайте вывод снова, чтобы проверить, что часовой пояс был изменен успешно
 */

//задание 1+2
$date=[mt_rand(0,time()),mt_rand(0,time()),mt_rand(0,time()),mt_rand(0,time()),mt_rand(0,time())];
        /*var_dump($date); //checkout block
        echo (date('d.m.Y',$date[0])).'<br/>';
        echo (date('d.m.Y',$date[1])).'<br/>';
        echo (date('d.m.Y',$date[2])).'<br/>';
        echo (date('d.m.Y',$date[3])).'<br/>';
        echo (date('d.m.Y',$date[4])).'<br/>';
        */
//3
echo 'Миниимальный день: '.min(date('d', $date[0]),date('d', $date[1]),date('d', $date[2]),date('d', $date[3]),date('d', $date[4])).'<br/>';
echo 'Максимальный месяц: '.max(date('m', $date[0]),date('m', $date[1]),date('m', $date[2]),date('m', $date[3]),date('m', $date[4])).'<br/>';
//4
sort($date);
//5
$selected = array_pop($date);
//6
echo 'popped from array: '.date('d.m.y H:i:s', $selected).'<br/>';
//7
date_default_timezone_set('America/New_York');
echo 'popped from array(timezone America/New_York):'.date('d.m.y H:i:s', $selected).'<br/>';
?>
 