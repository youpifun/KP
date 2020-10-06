<?php
session_start();
?>
<html>
<head>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
    <title>Catalog</title>
	<link rel="stylesheet" href="styles/search.css">
	<link rel="stylesheet" href="styles/styles.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
	<script src="search/search.js"></script>
</head>

<body style="background: GhostWhite;">
		<div class="topnav2">
		<a href="index.php">Main</a>
		<a class="active" href="catalog.php">Catalog</a>
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
        include ("bd.php");
        $id=$_SESSION['id'];
        $result = mysql_query("SELECT * FROM users WHERE id='$id'", $db);
        $myrow = mysql_fetch_array($result);
        $UserType = $myrow['UserType'];
         if($_POST){
            if(isset($_POST['PutInCart'])){
              PutInCart();
            }
         }

//добавить в корзину, обновить статус в Sells, убрать цену, и имя продукта (заменить на ID)
        function PutInCart()
        {
            $ProductID =  $_POST['buy'];
            $Customer = $_SESSION['id'];
            $Status = 'InCart';
            mysql_query("INSERT INTO Sells (id_Customer, ProductID, Status) VALUES ('$Customer','$ProductID','$Status')");
        }
        ?>
	<div id="searchBlock">
	<div id="searchField" class="active-cyan-4 mb-4" style="width: 200px">
		<input type="text" name="products" placeholder="Search" value="" class="form-control"  autocomplete="off">
	</div>
	<a class="lupka" onclick="test();">
		<img src="search/lupka.jpg" style="width: 50px; heigth: 50px; margin-top: 5px; margin-left: 5px;">
	</a>
	<ul class="search_result" style="position: absolute; z-index: 1000;"></ul>
	</div>
    <div class="offer-list" style="display: flex;">
		<div class="col-2" style="margin-top: 1%">
		<?
			$Result = mysql_query("SELECT DISTINCT Category FROM Products", $db);
			$Category = $_POST['Category'];
			if (($Result != null)) {
			?><form method="post">
			<?
			while ($RowResult = mysql_fetch_array($Result)) {
				?>
				<input type="radio" name="Category" 
				<? if($Category == $RowResult['Category']) {?> checked <?}
				?> value="<?echo $RowResult['Category']?>">
				<?echo $RowResult['Category']?></br>
			<?}
			} else {
				?>
					<p>No categories.</p>
				<?
			} 
		?>	
			<a href="catalog.php" class="btn btn-secondary">Clear</a>
			<input type="submit" class="btn btn-primary" value="Filter">
			</form>
		
		</div>
        <?php
		if (isset($_GET['ids'])) {
			$ids = $_GET['ids'];
        $result = mysql_query("SELECT * FROM Products WHERE id IN ($ids)", $db); } 
		if (isset($_POST['Category'])){
			$Category = $_POST['Category'];
			$result = mysql_query("SELECT * FROM Products WHERE Category='$Category'", $db);
		}
		if ((!isset($_GET['ids']))&&(!isset($_POST['Category']))){
		$result = mysql_query("SELECT * FROM Products", $db);}
		if ($result != null) {?>
			<div class="row row-cols-1 row-cols-md-3" style="width: 83%; margin-top: 1%;">
			<?
        while ($myrow1 = mysql_fetch_array($result))
        {
            ?>	
		
            <form method="post">
				<div class="col mb-4">
                <div class="card h-100">
					<div class="card-body">
					<h5 class="card-title"><?php echo $myrow1['ProductName'];?></h5>
                    <p class="card-text">Price: <?php echo $myrow1['Price'];?></p>
                <?if (($_SESSION['login']!='')and($UserType == 'Customer')):?>
                <input type="hidden" name="buy" value="<?echo $myrow1['id'];?>"/>
                <input type="submit" class="btn btn-primary" name="PutInCart" value="Put In Cart"/>
                <?endif;?>
				</div>
				</div>
				</div>
            </form>
        <?php
        } ?>
		</div>
		<?
		} else {?>
        <h3>Nothing found</h3> <?}?>
    </div>
</body>
</html>