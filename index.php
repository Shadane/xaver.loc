<?php
    error_reporting(E_ALL|E_ERROR|E_WARNING|E_PARSE|E_NOTICE);
    ini_set('display_errors', 1);
 
/*
 * Задание 1
 * - Создайте переменную $name и присвойте ей значение содержащее ваше имя, например Игорь
 * - Создайте переменную $age и присвойте ей ваше количество лет, например 30
 * - Выведите на экран фразу по шаблону "Меня зовут Игорь"
 *                                      "Мне 30 лет"
 * Удалите переменные $name и $age
 */
    $name='Александр';
    $age=24;
    print("Меня зовут $name. \n").'<br/>';
    echo ('Мне '.$age.' года.').'<br/>';
    unset($name,$age);
    
   
 /* 
 * Задание 2
 * - Создайте константу и присвойте ей значение города в котором живете
 * - Прежде чем выводить константу на экран, проверьте, действительно ли она объявлена и существует
 * - Выведите значение объявленной константы
 * - Попытайтесь изменить значение созданной константы
 */
   
    define('city', 'novosibirsk');
    if (city){
        echo city .'<br/>';
    }
    define('city', 'moscow'); //Notice: Constant city already defined in /var/www/public_html/index.php on line 35
 /* Задание 3
 * - Создайте ассоциативный массив $book, ключами которого будут являться значения "title", "author", "pages"
 * - Заполните его по логике описания книг, укажите значения книги, которую недавно прочитали
 * - Выведите следующую строку на экран, следуя шаблону: "Недавно я прочитал книгу 'title', написанную автором author, я осилил все pages страниц, мне она очень понравилась"
 */
    $book=['title'=>'Хроники амбера', 'author'=>'Роджер Желязны', 'pages'=>'1280'];
    echo 'Недавно я прочитал книгу '.$book['title'].', написанную автором '.$book['author'].', я осилил все '.$book['pages'].' страниц, мне она очень понравилась!'.'<br/>';


 /* Задание 4
 *  - Создайте индексный массив $books, который будет содержать в себе два массива $book1 и $book2, где будут записаны уже две последние прочитанные вами книги
 *  - Выведите следующую строку на экран, следуя шаблону: "Недавно я прочитал книги 'title1' и 'title2', 
 *  написанные соответственно авторами author1 и author2, я осилил в сумме pages1+pages2 страниц, не ожидал от себя подобного"
 */
    $book2=['title'=>'Летописи разлома', 'author'=>'Ник Перумов', 'pages'=>'2400'];
    $books=array($book,$book2);
    var_dump($books);/* вот что выдает(для себя)
 * array (size=2)
  0 => 
    array (size=3)
      'title' => string 'Хроники амбера' (length=27)
      'author' => string 'Роджер Желязны' (length=27)
      'pages' => string '1280' (length=4)
  1 => 
    array (size=3)
      'title' => string 'Летописи разлома' (length=31)
      'author' => string 'Ник Перумов' (length=21)
      'pages' => string '2400' (length=4)
 * 
 */
    echo 'Недавно я прочитал книги '.$books[0]['title'].' и '.$books[1]['title']. ', написанные соответственно авторами '.$books[0]['author'].' и '.$books[1]['author'].', я осилил в сумме '.($books[0]['pages']+$books[1]['pages']).' страниц, не ожидал от себя подобного.';

 ?>
 