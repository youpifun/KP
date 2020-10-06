<?php
session_start();
?>
<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
<link rel="stylesheet" href="styles/styles.css">
</head>

<body>	
	<div class="topnav2">
		<a class="active" href="#home">Main</a>
		<a href="catalog.php">Catalog</a>
		<a href="#contact">Contact</a>
		<a href="#about">About</a>
		<?php if (empty($_SESSION['login']) or empty($_SESSION['id']))
                { ?>
				<a class="right2" href="main2.php">Login</a> 
				<?; }
				else
				{ ?>
					<div class="dropdown2">
					 <a href="#" class="right2" id="log"><?echo $_SESSION['login']?></a>
						<div class="dropdown-child2">
							<a href="profile.php">Cart</a>
							<a href="info.php">Profile</a>
							<a href="index.php?logout=1">Log out</a>
						</div>
					</div>
				<?}?>
	</div>
			
           
            <?php
                function logout() {
                    unset($_SESSION['login'], $_SESSION['id']);
                    session_destroy();
                }
                if($_GET){
                    if($_GET['logout'] == 1) {
                        logout();
						echo "<meta http-equiv=\"refresh\" content=\"0;URL=index.php\">";
                    }
                }
				
				?>
   <h5 style="margin-left: 10%; margin-top: 5%; color: DodgerBlue;">What to do if you are ...</h5>
   <div id="info-block">
		<div class="info-child">
			<h6>Customer</h6>
				<ul>
					<li>Register as customer;
					<li>Log in your account;
					<li>Select the necessary product in the catalog;
					<li>Download receipt in your cart;
					<li>Upload a picture of a paid receipt;
					<li>Get your QR-code in profile page.
				</ul>
		</div>
		
		<div class="info-child">
			<h6>Seller</h6>
				<ul>
					<li>Register as seller;
					<li>Log in your account;
					<li>Put your item in the catalog from your cart page;
					<li>Check a picture of paid receipt;
					<li>Confirm a deal.
				</ul>
		</div>
	</div>
          
           
			
</body>
</html>