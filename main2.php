<?php session_start(); ?>
<!DOCTYPE html>

<html>

<head>
	<link rel="stylesheet" href="styles/2.css">
</head>

<body>
<a href="index.php" style="position: absolute; text-decoration: none; margin-left: 2%; margin-top: 2%;">Back</a>
<div id="borderBlock">
 </div>
<form action="handler.php" method="post">
<?php 
		//$check = $_SERVER['QUERY_STRING'];
		if ($_SESSION['check'] == '1') {?>  
			<div id="msgBlock">
				Wrong login or password
			</div><?
		}
		if ($_SESSION['check'] == '2') {?>
			<div id="msgBlock" style="font-size: 16px;">
				This login is already exists
			</div><?
		}
		if ($_SESSION['check'] == '3') {?>
			<div id="msgBlock" style="font-size: 16px; color: green;">
				You are successfully registered
			</div><?
		}
		session_destroy();?>
  <input checked="" id="signin" name="action" type="radio" value="signin" onclick="uncheckAllRadio('User-type')"/>
  <label for="signin">Sign in</label>
  <input id="signup" name="action" type="radio" value="signup" onclick="checkRadio('Customer')">
  <label for="signup">Sign up</label>
  <div id="wrapper">
    <div id="arrow"></div>
    <input name="login" size="15" maxlength="15" required placeholder="Login" type="text">
    <input id="pass" name="password" size="15" maxlength="15" required placeholder="Password" type="password">
    <input id="Customer" class="UserType" name="User-type" type="radio" value="Customer">
	<label for="Customer">Customer</label>
	<input id="Seller" class="UserType" name="User-type" type="radio" value="Seller">
	<label for="Seller">Seller</label>
  </div>
  <button type="submit" name="submit" value="Enter">
    <span>
		<br>
      Sign in
      <br>
      Sign up
    </span>
  </button>
  
</form>
 
<script type="text/javascript">
	 function uncheckAllRadio(name){
    var obj = document.getElementsByName(name);
    for(i=0; i<2; i++)
      obj[i].checked = false;
  }
  function checkRadio(id){
	document.getElementById(id).checked = true;
  }
</script>

</body>
</html>