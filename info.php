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
	<h4 style="margin-top: 2%; margin-left: 2%;">Check your statistics:</h4>
	<a href="statistics.php" class="btn btn-primary" style="margin-top: 1%; margin-left: 2%; margin-bottom: 2%;">Check statistics</a>
	<?
	if ($UserType=='Customer') {
		$CustomerID = $_SESSION['id'];
		$result = mysql_query("SELECT Products.ProductName, Sells.id, Sells.QRCode, Sells.Date, users.login, Products.Price 
									FROM Sells,Products,users WHERE id_Customer = '$CustomerID' 
									AND Sells.ProductID = Products.id 
									AND Sells.ProductID = Products.id AND Products.SellerID = users.id 
									AND Sells.Status ='SellCompleted'", $db);
    ?>
	<h3 style="margin-left: 2%;">History of Buys</h3>
	<table class="table w-75" style="margin-left: 2%;">
	  <thead>
    <tr>
      <th scope="col">#</th>
      <th scope="col">Product name</th>
      <th scope="col">Price</th>
      <th scope="col">Date</th>
	  <th scope="col">Check QRCode</th>
    </tr>
	</thead>
	<?	
		$counter = 1;
		while ($myrow = mysql_fetch_array($result))
            {
                ?>
            <tr>	
				<td><?=$counter++;?></td>
                <td><?= $myrow['ProductName']?></td>
                <td><?= $myrow['Price']?></td>
				<td><?=$myrow['Date']?></td>
				<td>
					<button type="button" class="btn btn-primary" data-toggle="modal" 
						data-target="#exampleModal" data-whatever="<?=$myrow['QRCode']?>">Check
					</button>	
				</td>
            </tr>
        <?php
    }
        ?></table>

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Your QRCode</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
       <img src = 'QRgen.php?code='>
      </div>
      
    </div>
  </div>
</div>
			
		<?
	}
	if ($UserType=="Seller") {
		$SellerID = $_SESSION['id'];
		$result = mysql_query("SELECT users.login AS Customer, 
							   Sells.Date AS Date, Products.ProductName 
							   AS Product, Sells.QRCode AS QRCode FROM users, Sells, Products 
							   WHERE Sells.Status='SellCompleted' AND Sells.id_Customer=users.id 
							   AND Sells.ProductID=Products.id AND Products.SellerID='$SellerID' 
							   ORDER BY Date DESC", $db);
		?>
		<h3 style="margin-left: 2%;">History of sells</h3>
			<table class="table w-75" style="margin-left: 2%;">
			<thead>
				<tr>
					<th scope="col">#</th>
					<th scope="col">Customer name</th>
					<th scope="col">Date</th>
					<th scope="col">Product name</th>
					<th scope="col">QRCode</th>
				</tr>
			</thead>
			<?	
			$counter = 1;
				while ($myrow = mysql_fetch_array($result))
					{?>
					<tr>	
						<td><?=$counter++;?></td>
						<td><?=$myrow['Customer']?></td>
						<td><?=$myrow['Date']?></td>
						<td><?=$myrow['Product']?></td>
						<td><?=$myrow['QRCode']?></td>
					</tr>
				<?php
				}?>
			</table>
		<?
	}
	?>
</body>
   <script>		
  $('#exampleModal').on('show.bs.modal', function (event) {
  var button = $(event.relatedTarget) // Button that triggered the modal
  var recipient = button.data('whatever');
  var modal = $(this);
  var img = modal.find('.modal-body img');
  img.attr('src', img.attr('src')+recipient);
  console.log(recipient);
})
   </script>
</html>



