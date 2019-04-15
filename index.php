<?php
    session_start();
    ?>
<html>
<head>
    <title>Main Page</title>
</head>
<body>
<h2>Main Page</h2>
<form action="handler.php" method="post">


    <p>
        <label>Your login:<br></label>
        <input name="login" type="text" size="15" maxlength="15">
    </p>

    <p>

        <label>Your password:<br></label>
        <input name="password" type="password" size="15" maxlength="15">
    </p>

    <p>
        <input type="submit" name="submit" value="Enter">


        <br>

        <a href="reg.php">Registration</a>
    </p></form>
<br>
<?php
    if (empty($_SESSION['login']) or empty($_SESSION['id']))
    {
    echo "You entered as guest<br><a href='#'> This ref is only for registered users</a>"; }
else
{
echo "You entered as ".$_SESSION['login']."<br><a  href='http://google.com/'>This ref is only for registered users</a>";
}
?>
</body>
</html>