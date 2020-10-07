<?php
session_start();
include "phpqrcode/qrlib.php";
?>
<html>
<head>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
   <link rel="stylesheet" href="styles/styles.css">
      <script src="profile/bootstrap-filestyle.min.js"></script>
   <script src="profile/profile.js"></script>
   <meta charset="UTF-8"/>
    <title>Cart</title>
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
if($UserType=='Customer'):
    function GetReceipt()
    {
        $templatePath = 'docs/template2.doc';
        $outputPath = 'docs/temp.doc';
        copy($templatePath, $outputPath);
        $file = file_get_contents($outputPath);
        $file = str_replace("%PRODUCTNAME%", $_POST['ProductName'], $file);
        $file = str_replace("%SELLERNAME%", $_POST['Seller'], $file);
        $file = str_replace("%CUSTOMERNAME%", $_SESSION['login'], $file);
        $file = str_replace("%PRICE%", $_POST['Price'], $file);
        $output = fopen($outputPath, 'w');
        fwrite($output, $file);
        fclose($output);
        echo"<script> 
                 var link=document.createElement('a'); 
                link.setAttribute('href','docs/temp.doc'); 
                link.setAttribute('download','Накладная от ".$_POST['Seller']."'); 
                link.setAttribute('target','_blank'); 
                link.click(); 
            </script>";
        $id = $_POST['SellsID'];
        mysql_query("UPDATE Sells SET Sells.Status = 'WaitingReceipt' WHERE Sells.id = '$id'");
    }
    function UploadReceipt() {
        $id = $_POST['SellsID'];
        $k = $_FILES['userfile']['tmp_name'];
        $f = fopen("$k", "rb");
        $upload = fread($f,filesize("$k"));
        fclose($f);
        $upload=addslashes($upload);
        mysql_query("UPDATE Sells SET Sells.Receipt = '$upload', Sells.Status = 'ReceiptApplied' WHERE Sells.id = '$id'");
        exit("You have successfully sent a receipt <br><br><a href='profile.php'>Back");
    }
    ?>
<div class="offer-list">
    <div class="offer-item" style="margin-left: 2%;">
        <?php

        if($_POST){
            if(isset($_POST['GetReceipt'])){
                GetReceipt();
            }
            if(isset($_POST['Upload'])){
                $ext = explode('.', $_FILES['userfile']['name'])[1];
                if(in_array($ext, ['php', 'html', 'docx' ,'pdf'], true)){
                    exit("Error! You only images can be uploaded. <br><br> <a href='profile.php'>Back<?");}
                UploadReceipt();
            }

        }
        ?><h3 style="color: DodgerBlue; margin-top: 2%;">Products, which waiting your receipt:</h3><?
        $CustomerID = $_SESSION['id'];
        $result = mysql_query("SELECT Sells.Status, Products.ProductName, Sells.id, users.login, Products.Price 
									  FROM Sells, Products,users 
                                      WHERE id_Customer = '$CustomerID' AND Sells.ProductID = Products.id 
                                      AND Products.SellerID = users.id AND Sells.Status 
									  NOT IN ('SellCompleted', 'ReceiptApplied')", $db);
        while ($myrow = mysql_fetch_array($result))
        {
            ?>
            <form method="post">
                <p>
                    Name:
                    <?php
                    echo $myrow['ProductName'];
                    ?>
                    <br>
                    Price:
                    <?php
                    echo $myrow['Price'];
                    ?>
                    <br>
					<? if ($myrow['Status'] == 'WrongReceipt') { ?>
					<span class="text-danger">Wrong receipt!</span>
				    <? } ?>
                </p>
                <input type="hidden" name="ProductName" value="<?=$myrow['ProductName'];?>"/>
                <input type="hidden" name="Seller" value="<?=$myrow['login'];?>"/>
                <input type="hidden" name="Customer" value="<?=$_SESSION['login'];?>"/>
                <input type="hidden" name="Price" value="<?echo $myrow['Price'];?>"/>
                <input type="hidden" name="SellsID" value="<?echo $myrow['id'];?>"/>
                Download receipt:
                <input type="submit" name="GetReceipt" value="Get Receipt"><br>
            </form>
            <form enctype="multipart/form-data" method="post">
                Upload receipt:
                <input type="hidden" name="SellsID" value="<?echo $myrow['id'];?>"/>
                <input type="hidden" name="MAX_FILE_SIZE" value="350000" />
				<div style="width: 350px;" class="col p-0"><input type="file" data-btnClass="mb-3 btn-primary" data-text="Choose receipt..." class="filestyle" required accept="image/*" name="userfile" value="Upload Receipt"></div>
                <input type="submit" class="btn-primary" name="Upload" value="Upload">
                <br><br>
            </form>
            <?php
        }
        ?>
    </div>
</div>
<?  else :?>

   <?php
    function sell()
    {
        global  $price, $ProductName, $id, $Category, $Description;
        $price = $_POST['price'];
        $ProductName = $_POST['ProductName'];
        $SellerID = $id;
		$Category = $_POST['Category'];
		$Description = $_POST['Description'];
        mysql_query ("INSERT INTO Products (ProductName,SellerID,Price,Category,Description) VALUES('$ProductName','$SellerID','$price','$Category','$Description')");
        exit("You have successfully placed your product in the catalog. <a href='profile.php'>Back");
    }

    if($_POST){
        if(isset($_POST['sell'])){
            $_POST['price']=str_replace(' ', '',$_POST['price']);
            $_POST['ProductName']=str_replace(' ', '',$_POST['ProductName']);
            if (empty($_POST['price']) or empty($_POST['ProductName'])){
                exit("Please fill all fields without using spaces <br><br><a href='profile.php'>Back<?");
            }
            else
            sell();
        }
    }
    ?>
<h3 style="margin-left: 1%; margin-top: 1%;">Sell something</h3>
<!-- Button trigger modal -->
<button type="button" class="btn btn-primary" style="margin-left:1%;" data-toggle="modal" data-target="#exampleModal">
  Put product in catalog
</button>

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Put product in catalog</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form method="post">
			<div style="margin-left: 50px;">
			Name your product: </br>
			<input type="text" class="w-75" required name="ProductName" placeholder="Name"></br>
			Set your price: </br>
			<input type="text" class="w-75" required name="price" placeholder="Price"> </br>
			Category of product:</br>
			<input type="text" class="w-75" name="Category"></br>
			Description of product: </br>
			<textarea name="Description" cols="40" style="width: 75%;" rows="3"></textarea>
			</div>
			<div class="modal-footer">
			<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
			<input type="submit" class="btn btn-primary" name="sell" value="Sell">
      </div>
		</form>
      </div>
      
    </div>
  </div>
</div>


<h3 style="margin-top: 2%; margin-left:1%;">Sells list:</h3>
<?php //SELECT users.login, Products.ProductName, Sells.Status FROM Sells INNER JOIN users ON Sells.id_Customer=users.id, Products WHERE SellerID = '2'
//вывод из таблицы Sells, всех продуктов, на которые получили квитанции покупатели у данного продавца
$SellerID = $_SESSION['id'];
$db = mysqli_connect ("localhost","root","password");
mysqli_select_db ($db,"users");
$resultRow = mysqli_query($db, "SELECT users.login, Products.ProductName, Sells.Status, Sells.id FROM Sells INNER JOIN users ON Sells.id_Customer=users.id, Products WHERE SellerID = '$SellerID' AND Sells.ProductID = Products.id AND (Sells.Status = 'WaitingReceipt' OR Sells.Status = 'ReceiptApplied')");
$myrow1 = mysqli_fetch_all($resultRow);
?>
<table class="table w-75" style="margin-left: 5%">
  <thead class="thead-light">
    <tr>
      <th scope="col">#</th>
      <th scope="col">Customer name</th>
      <th scope="col">Product name</th>
      <th scope="col">Status</th>
	  <th scope="col">Check receipt</th>
	  <th scope="col">Apply</th>
	  <th scope="col">Reject</th>
    </tr>
	</thead>
	<tbody>
    <?php
    function CheckApply(){
        $db = mysql_connect ("localhost","root","password");
        mysql_select_db ("users",$db);
        $id = $_POST['id'];
        $resultRow = mysql_query("SELECT Receipt From Sells WHERE id = '$id'", $db);
        $myrow = mysql_fetch_array($resultRow);
        $templatePath = 'image.jpg';
        $outputPath = 'image2.jpg';
        copy($templatePath, $outputPath);
        $file = file_put_contents($outputPath, '');
        $output = fopen($outputPath, 'w');
        fwrite($output, $myrow['Receipt']);
        fclose($output);
        echo"<script> 
                 var link=document.createElement('a'); 
                link.setAttribute('href','image2.jpg'); 
                link.setAttribute('target','_blank'); 
                link.click(); 
            </script>";
    }
    function remove() {
        $id = $_POST['id'];
		$CurDate = date("Y-m-d H:i:s");
		$QRCode = md5($_POST['id']+$_SESSION['login']);
        mysql_query("UPDATE Sells SET Status = 'SellCompleted', Date = '$CurDate', QRCode = '$QRCode'
                            WHERE Sells.id = '$id'");
		
	}
    if($_POST) {
        if(isset($_POST['CheckApply'])){
            CheckApply();
        }
        if(isset($_POST['Remove'])){
            remove();
			
        }
    }
	$counter = 1;
    foreach ($myrow1 as $index => $row) {
        echo '<tr><th scope="col">'.$counter++.'</th><td>'.$row[0].'</td><td>'.$row[1].'</td><td>'.$row[2].'</td><td>';
        if ($row[2] == 'WaitingReceipt')
        {echo 'Waiting Receipt</td><td></td><td></td>';}
        else {?>
            <form method="post" action="profile.php">
            <input type="hidden" name="id" value="<?echo $row[3]?>">
            <input type="hidden" name="CustomerID" value="<?echo $row[0]?>">
            <input class="btn btn-primary" type="submit" name="CheckApply" value="Check"></td><td>
                <input class="btn btn-primary" type="submit" name="Remove" value="Confirm"></td>
				<td><button onclick="rejectReceipt(<?=$row[3]?>);" class="btn pt-0 text-danger"><span class="h3" aria-hiddden="true">&times;</span></button></td></form><?};}
    endif;
    ?>	
		</tbody>
        </table>
</body>
</html>