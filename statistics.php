<?php
session_start();
include "phpqrcode/qrlib.php";
?>
<html>
<head>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
   <link rel="stylesheet" href="styles/styles.css">
   <meta charset="UTF-8"/>
</head>
<body>
<?php
include ("bd.php");
    $id=$_SESSION['id'];
    $login=$_SESSION['login'];
    $result = mysql_query("SELECT * FROM users WHERE id='$id'", $db);
    $myrow = mysql_fetch_array($result);
    $UserType=$myrow['UserType'];
	?>
	<div class="topnav2">
		<a href="index.php">Main</a>
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
					 <a href="#" class="right2 active" id="log"><?echo $_SESSION['login']?></a>
						<div class="dropdown-child2">
							<a href="profile.php">Cart</a>
							<a href="info.php">Profile</a>
							<a href="index.php?logout=1">Log out</a>
						</div>
					</div>
				<?}?>
	</div> 
	<?
		if ($UserType=='Customer') {
			$result = mysql_query("SELECT Products.ProductName AS ProductName, 
								   COUNT(*) AS AmountOfBuys  FROM Sells,Products 
								   WHERE Sells.ProductID = Products.id AND Sells.id_Customer = '$id' 
								   AND Sells.Status = 'SellCompleted' GROUP BY Products.id 
								   ORDER BY AmountOfBuys DESC", $db);
			?>
				<h5>Top of purchased goods (by amount)</h5>
				<table class="table w-75">
					<thead class="thead-light">
						<tr>
							<th scope="col">#</th>
							<th scope="col">Product name</th>
							<th scope="col">Amount of buys</th>
						</tr>
					</thead>
				<?	
					$counter = 1;
					while ($myrow = mysql_fetch_array($result)){?>
					<tr>	
						<td><?=$counter++;?></td>
						<td><?= $myrow['ProductName']?></td>
						<td><?= $myrow['AmountOfBuys']?></td>
					</tr>
					<?}?>
				</table>
			<?	
			$result2 = mysql_query("SELECT users.login AS SellerName, 
									COUNT(*) AS AmountOfBuys FROM users, Products, Sells 
									WHERE users.id = Products.SellerID AND Products.id = Sells.ProductID 
									AND Sells.id_Customer = '$id' AND Sells.Status = 'SellCompleted' 
									GROUP BY users.id ORDER BY AmountOfBuys DESC", $db);
			?>
			<h5>Your favorite vendors</h5>
			<table class="table w-75">
				<thead class="thead-light">
					<tr>
						<th scope="col">#</th>
						<th scope="col">Vendors name</th>
						<th scope="col">Amount of buys</th>
					</tr>
				</thead>
				<?	
			$counter = 1;
			while ($myrow = mysql_fetch_array($result2)){
                ?>
					<tr>	
					<td><?=$counter++;?></td>
                    <td><?= $myrow['SellerName']?></td>
                    <td><?= $myrow['AmountOfBuys']?></td>
					</tr>
				<?php
			}?>
			</table>
			<?
			$result3 = mysql_query("SELECT SUM(Products.Price) AS MoneySpent, Products.ProductName 
									AS ProductName FROM Sells,Products WHERE Sells.ProductID = Products.id 
									AND Sells.id_Customer='$id' AND Sells.Status = 'SellCompleted' 
									GROUP BY Sells.ProductID ORDER BY MoneySpent DESC", $db);
			?>
			<h5>Top of purchased goods (by money spent)</h5>
			<table class="table w-75">
					<thead class="thead-light">
						<tr>
							<th scope="col">#</th>
							<th scope="col">Product name</th>
							<th scope="col">Money spent</th>
						</tr>
					</thead>
				<?	
			$counter = 1;
			while ($myrow = mysql_fetch_array($result3)){
                ?>
					<tr>	
					<td><?=$counter++;?></td>
                    <td><?= $myrow['ProductName']?></td>
                    <td><?= $myrow['MoneySpent']?></td>
					</tr>
				<?php
			}?>
			</table>
			<?
			$result4 = mysql_query("SELECT DATE_FORMAT(Sells.Date, '%M, %Y') 
									AS SellDate, SUM(Products.Price) AS SpentMoney FROM Sells, 
									Products WHERE Sells.ProductID=Products.id AND Sells.id_customer='$id' 
									AND TIMESTAMPDIFF(MONTH, Sells.Date, NOW())<=12 
									AND Sells.Status = 'SellCompleted' GROUP BY SellDate 
									ORDER BY SpentMoney DESC", $db);
			?>
			<h5>Top of months by money spent in last year</h5>
			<table class="table w-75">
					<thead class="thead-light">
						<tr>
							<th scope="col">#</th>
							<th scope="col">Month</th>
							<th scope="col">Money spent</th>
						</tr>
					</thead>
				<?	
			$counter = 1;
			while ($myrow = mysql_fetch_array($result4)){
                ?>
					<tr>	
					<td><?=$counter++;?></td>
                    <td><?= $myrow['SellDate']?></td>
                    <td><?= $myrow['SpentMoney']?></td>
					</tr>
				<?php
			}?>
			</table>
			<?
		} 
		if ($UserType == 'Seller') {
			$result = mysql_query("SELECT COUNT(*) AS AmountOfSells, Products.ProductName 
								   AS ProductName FROM Sells,Products WHERE Sells.ProductID = Products.id 
								   AND Products.SellerID = '$id' AND Sells.Status = 'SellCompleted' 
								   GROUP BY Sells.ProductID ORDER BY AmountOfSells DESC", $db);
			?>
				<h5>Top of sold goods (by amount)</h5>
				<table class="table w-75">
					<thead class="thead-light">
						<tr>
							<th scope="col">#</th>
							<th scope="col">Product name</th>
							<th scope="col">Amount of sells</th>
						</tr>
					</thead>
				<?	
				$counter = 1;
				while ($myrow = mysql_fetch_array($result)){
				?>
					<tr>	
						<td><?=$counter++;?></td>
						<td><?= $myrow['ProductName']?></td>
						<td><?= $myrow['AmountOfSells']?></td>
					</tr>
				<?php
				}?>
				</table>
			<?	
			$result2 = mysql_query("SELECT SUM(Products.Price) AS MoneyReceived, Products.ProductName 
									AS ProductName FROM Sells,Products WHERE Sells.ProductID = Products.id 
									AND Products.SellerID = '$id' AND Sells.Status = 'SellCompleted' 
									GROUP BY Sells.ProductID ORDER BY MoneyReceived DESC", $db);
			?>
			<h5>Top of sold goods (by total price)</h5>
			<table class="table w-75">
					<thead class="thead-light">
						<tr>
							<th scope="col">#</th>
							<th scope="col">Product name</th>
							<th scope="col">Total money recived by selling this product</th>
						</tr>
					</thead>
				<?	
			$counter = 1;
			while ($myrow = mysql_fetch_array($result2))
            {
            ?>
            <tr>	
					<td><?=$counter++;?></td>
                    <td><?= $myrow['ProductName']?></td>
                    <td><?= $myrow['MoneyReceived']?></td>
            </tr>
			<?php
			}?>
			</table>
			<?
			$result3 = mysql_query("SELECT COUNT(Sells.id) AS SellsAmount,users.login 
									AS Customer FROM Sells, users, Products 
									WHERE Sells.id_Customer = users.id 
									AND Sells.ProductID = Products.id AND Products.SellerID = '$id' 
									AND Sells.Status = 'SellCompleted' GROUP BY users.id 
									ORDER BY SellsAmount DESC", $db);
			?>
			<h5>Top of customers (by amount of buys)</h5>
			<table class="table w-75">
					<thead class="thead-light">
						<tr>
							<th scope="col">#</th>
							<th scope="col">Customer name</th>
							<th scope="col">Amount of buys</th>
						</tr>
					</thead>
				<?	
			$counter = 1;
			while ($myrow = mysql_fetch_array($result3))
            {
            ?>
            <tr>	
					<td><?=$counter++;?></td>
                    <td><?= $myrow['Customer']?></td>
                    <td><?= $myrow['SellsAmount']?></td>
            </tr>
			<?php
			}?>
			</table>
			<?
			$result4 = mysql_query("SELECT DATE_FORMAT(Sells.Date, '%M, %Y') 
									AS SellDate, SUM(Products.Price) AS ReceivedMoney FROM Sells, Products 
									WHERE Sells.ProductID=Products.id AND Products.SellerID='$id' 
									AND TIMESTAMPDIFF(MONTH, Sells.Date, NOW())<=12 
									AND Sells.Status = 'SellCompleted' GROUP BY SellDate 
									ORDER BY ReceivedMoney DESC", $db);
			?>
			<h5>Top months by recived money in last year</h5>
			<table class="table w-75">
					<thead class="thead-light">
						<tr>
							<th scope="col">#</th>
							<th scope="col">Month</th>
							<th scope="col">Received money</th>
						</tr>
					</thead>
				<?	
			$counter = 1;
			while ($myrow = mysql_fetch_array($result4))
            {
            ?>
            <tr>	
					<td><?=$counter++;?></td>
                    <td><?= $myrow['SellDate']?></td>
                    <td><?= $myrow['ReceivedMoney']?></td>
            </tr>
			<?php
			}?>
			</table>
			<?
		}
	?>
</body>
</html>