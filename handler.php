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

include ("bd.php");

$result = mysql_query("SELECT * FROM users WHERE login='$login'",$db);
$myrow = mysql_fetch_array($result);
if (empty ($myrow['password']))
{

    exit ("Sorry, your login or password are not correct.");
}
else {

    if ($myrow['password']==$password) {

        $_SESSION['login']=$myrow['login'];
        $_SESSION['id']=$myrow['id'];
        echo "You entered! <a href='index.php'>Main page</a>";
    }
    else {


        exit ("Sorry, your login or password are not correct");
    }
}
?>