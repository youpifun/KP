<?php
session_start();
?>
<html>
<head>
    <title>Catalog</title>
</head>

<body>
    Your balance:  <?php
        include ("bd.php");
        $id=$_SESSION['id'];
        $result = mysql_query("SELECT * FROM users WHERE id='$id'", $db);
        $myrow = mysql_fetch_array($result);
        $balance = $myrow['balance'];
        echo $balance;
        if($_POST){
            if(isset($_POST['buy'])){
                buy();
            }
        }

        function buy()
        {

            ;
        }
        ?><br>
    <div class="offer-list">
        <h2>Catalog</h2>
        <?php
        $Status='InMarket';
        $result = mysql_query("SELECT * FROM Products WHERE Status = '$Status'", $db);


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
                </p>
                <input type="submit" name="buy" value="buy"/>
            </form>
        <?php
            print_r($myrow);
        }
        ?>

            <form method="post">
                <!--<input type="text" name="txt" /> -->

                <p>
                    <a href="index.php" class="auth-button">Return
                    </a>
                </p>
            </form>

    </div>

</body>
</html>