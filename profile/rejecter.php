<?php
define("DB_HOST","localhost");
define("DB_NAME","users"); //Имя базы
define("DB_USER","root"); //Пользователь
define("DB_PASSWORD","password"); //Пароль
define("PREFIX",""); //Префикс если нужно
$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
$mysqli -> query("SET NAMES 'utf8'") or die ("Ошибка соединения с базой!");
if(!empty($_POST["sell_id"])){
    $sell_id = trim(strip_tags(stripcslashes(htmlspecialchars($_POST["sell_id"]))));
    $db_products = $mysqli -> query("UPDATE Sells SET Status = 'WrongReceipt' WHERE Sells.id = '$sell_id'")
    or die('Ошибка №'.__LINE__.'<br>Обратитесь к администратору сайта пожалуйста, сообщив номер ошибки.');
}
?>