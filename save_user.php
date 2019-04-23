<?php
if (isset($_POST['login'])) { $login = $_POST['login']; if ($login == '') { unset($login);} }
if (isset($_POST['password'])) { $password=md5($_POST['password']); if ($password =='') { unset($password);} }

if (empty($login) or empty($password))
                                         {
                                             exit ("Please, fill all fields.");
                                         }

$login = stripslashes($login);
$login = htmlspecialchars($login);
$password = stripslashes($password);
$password = htmlspecialchars($password);
$login = trim($login);
$password = trim($password);

   include ("bd.php");
    $result = mysql_query("SELECT id FROM users WHERE login='$login'",$db);
$myrow = mysql_fetch_array($result);
if (!empty($myrow['id'])) {
    exit ("Your login already exists. Try to other login.");
}

    $result2 = mysql_query ("INSERT INTO users (login,password) VALUES('$login','$password')");

if ($result2=='TRUE')
{
    echo "You successful registered! Now you can login.<a href='main.php'>Main page</a>";
}
else {
    echo "Error! You are not registered.";
}
?>