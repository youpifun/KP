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
				<a href="catalog.php">Catalog</a> | <?php
                function logout() {
                    unset($_SESSION['login'], $_SESSION['id']);
                    session_destroy();
                }
                if($_GET){
                    if(isset($_GET['logout'])) {
                        logout();
                    }
                }
                if (empty($_SESSION['login']) or empty($_SESSION['id']))
                {
                    echo "You entered as guest"; }
                else
                {
                    echo "You entered as ".$_SESSION['login'];
                }
                ?> |
                <form>
                <input type="submit" name="logout" value="logout" />
                </form>
                    <?php
                ?>
			</nav>
			<form>
				<a href="main.php" class="auth-button">Login/Register
				</a>
			</form>
            <?php print_r($_SESSION); ?>
		</header>
</body>
</html>