<?php
session_start();
?>
<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="../css/single.css">
</head>

<body>
		<header>
			<nav>
				<a href="catalog.html">Catalog</a> | <?php
                if (empty($_SESSION['login']) or empty($_SESSION['id']))
                {
                    echo "You entered as guest"; }
                else
                {
                    echo "You entered as ".$_SESSION['login'];
                }
                ?>
			</nav>
			<form>
				<a href="main.php" class="auth-button">Login/Register
				</a>
			</form>
		</header>
</body>
</html>