<html>
<head>
    <title>Register</title>
</head>
<body>
<h2>Register</h2>
<form action="save_user.php" method="post">
    <p>
        <label>Your login:<br></label>
        <input name="login" required type="text" size="15" maxlength="15">
    </p>
    <p>
        <label>Your password:<br></label>
        <input name="password" required type="password" size="15" maxlength="15">
    </p>
    <p>
        <label>Who are you?<br></label>
        <select name="User-type">
            <option value="Customer">Customer</option>
            <option value="Seller">Seller</option>
        </select>
    </p>
    <p>
        <input type="submit" name="submit" value="Register">
    </p></form>
    <br>
    <br>
        <a href="main.html">Back</a>
</body>
</html>