<?php
session_start();
if (isset($_POST['login'])) { $login = $_POST['login']; if ($login == '') { unset($login);} }
if (isset($_POST['password'])) { $password=md5($_POST['password']); if ($password =='') { unset($password);} }

if (empty($login) or empty($password))
{
    exit ("Please fill all fields");
}

$login = stripslashes($login);
$login = htmlspecialchars($login);
$password = htmlspecialchars($password);

$login = trim($login);
$password = trim($password);
$User_type=$_POST['User-type'];
include ("bd.php");
$result = mysql_query("SELECT * FROM users WHERE login='$login'",$db);
$myrow = mysql_fetch_array($result);
session_start();
if ($User_type == null){
    if ($myrow['password']==$password) {
        $_SESSION['login']=$myrow['login'];
        $_SESSION['id']=$myrow['id'];
		header('Refresh: 0.1; url=index.php');
    }
    else {
		$_SESSION['check'] = 1;
		session_write_close();
        header('Refresh: 0.1; url=main2.php');
    }
} else {
	if (!empty($myrow['id'])) {
		$_SESSION['check'] = 2;
		session_write_close();
		header('Refresh: 0.1; url=main2.php');
		exit();
}

    $result2 = mysql_query ("INSERT INTO users (login,password,UserType) VALUES('$login','$password','$User_type')");

if ($result2=='TRUE')
{
	$_SESSION['check'] = 3;
	session_write_close();
    header('Refresh: 0.1; url=main2.php');
	exit();
}
else {
    echo "Error! You are not registered.";
}
}
?>