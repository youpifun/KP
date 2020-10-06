<?php
define("DB_HOST","localhost");
define("DB_NAME","users"); //Имя базы
define("DB_USER","root"); //Пользователь
define("DB_PASSWORD","password"); //Пароль
define("PREFIX",""); //Префикс если нужно
$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
$mysqli -> query("SET NAMES 'utf8'") or die ("Ошибка соединения с базой!");
if(!empty($_POST["products"])&&(str_replace(' ', '', $_POST['products']) != '')){ //Принимаем данные
    $products = trim(strip_tags(stripcslashes(htmlspecialchars($_POST["products"]))));
    $db_products = $mysqli -> query("SELECT * from Products WHERE ProductName LIKE '%$products%'")
    or die('Ошибка №'.__LINE__.'<br>Обратитесь к администратору сайта пожалуйста, сообщив номер ошибки.');
	$ids = [];
	$names = [];
		while ($row = $db_products -> fetch_array()) {
			$id=$row['id'];
			array_push($ids, $id);
			array_push($names, $row['ProductName']); 
		}
		if (!empty($ids))
        echo join(';', [join(',', $ids), join(',', $names)]); //$row["name"] - имя поля таблицы
}
?>