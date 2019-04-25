<html>
<head>
    <title>Catalog</title>
</head>

<body>
    <p>Your balance:  <?php
        print_r($_SESSION);
        include ("bd.php");
        $id=$_SESSION['id'];
        $result = mysql_query("SELECT * FROM users WHERE id='$id'", $db);
        $myrow = mysql_fetch_array($result);
        $balance = $myrow['balance'];

        if($_GET){
            if(isset($_GET['fill_balance'])){
                fill_balance();
            }elseif(isset($_GET['buy'])){
                buy();
            }
        }
        function fill_balance()
        {

        }

        function buy()
        {

            echo "The buy function is called.";
        }
        ?></p><br>
    <div class="offer-list">
        <div class="offer-item">
            <form>
                <!--<input type="text" name="txt" /> -->
                <input type="submit" name="fill_balance" value="fill_balance" />
                <input type="submit" name="buy" value="buy" />
            </form>
        </div>
    </div>

</body>
</html>