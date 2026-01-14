<?php

session_start();


    //database connection
    $servername = "localhost";
    $username = "root";
    $password = "";

    // Create connection
    $conn = new mysqli($servername, $username, $password, 'shopping');
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    if(isset($_POST['addcart'])){

        if(isset($_SESSION['shoppingcart'])){

            $itemarrayid=array_column($_SESSION['shoppingcart'],'itemid');
            if(!in_array($_GET['id'], $itemarrayid)){
                $count = count($_SESSION['shoppingcart']);
                $itemarray = array(
                    'itemid'=>$_GET['id'],
                    'itemname'=>$_POST['iname'],
                    'itemprice'=>$_POST['iprice'],
                    'itemquantity'=>$_POST['quantity']
                );
                $_SESSION['shoppingcart'][$count]=$itemarray;
            }else{
                echo '<script> alert("the item is already added")</script>';
            }

        }else{
            $itemarray = array(
                'itemid'        =>  $_GET['id'],
                'itemname'      =>  $_POST['iname'],
                'itemprice'     =>  $_POST['iprice'],
                'itemquantity'  =>  $_POST['quantity']
            );

            $_SESSION['shoppingcart'][0]=$itemarray;
        }
    }
    if(isset($_GET['action'])){
        if($_GET['action']=='delete'){

            foreach ($_SESSION['shoppingcart'] as $keys => $values){
                if($values['itemid'] == $_GET['id']){
                    unset($_SESSION['shoppingcart'][$keys]);
                    echo '<script> alert("item removed")</script>';

                }
            }
        }
    }

?>


<!DOCTYPE html>
<html>
<head>
    <title>Shopping Cart</title>

    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">

    <!-- jQuery library -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

    <!-- Latest compiled JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>


</head>
<body>

    <br>
    <div class="container">
        <br>
        <h2 align="center">Shopping Cart in PHP</h2>
        <?php
            $query = "Select * from tbl_product ORDER by id ASC";
            $result = $conn->query($query);
            if($result->num_rows > 0){

                while($row = $result->fetch_assoc()){




            ?>
        <div class="col-md-4">
            <form method="post" action="index.php?action=add&id=<?php echo $row['id']; ?>">
                <div style="border: 3px solid blue; background-color: lightgreen; border-radius: 5px; padding: 16px" align="center">
                <img src="image/<?php echo $row['image']; ?>" class="img-responsive">
                </div>
                <h3 class="text-info"><?php echo $row['name']; ?></h3>
                <h3 class="text-danger"><?php echo "$".$row['price']; ?></h3>
                <input type="text" name="quantity" value="1" class="form-control">
                <input type="hidden" name="iname" value="<?php echo $row['name']; ?>">
                <input type="hidden" name="iprice" value="<?php echo $row['price']; ?>">
                <input type="submit" name="addcart" style="margin-top: 5px" class="btn btn-success" value="Add to Cart">
            </form>
        </div>
        <?php
                }
            }
        ?>
        <div style="clear:both"></div> <br>
        <h2> Order Detail</h2>
        <div class="table-responsive">
            <table class="table table-bordered">
             <tr>
                 <th width="30%">Item Name</th>
                 <th width="10%">Quantity</th>
                 <th width="20%">Price</th>
                 <th width="15%">Total </th>
                 <th width="5%">Action</th>
             </tr>
                <?php
                    if(!empty($_SESSION['shoppingcart'])){
                        $total = 0;
                        foreach ($_SESSION['shoppingcart'] as $keys => $values){
                        ?>
                <tr>
                    <td><?php echo $values['itemname']; ?></td>
                    <td><?php echo $values['itemquantity']; ?></td>
                    <td><?php echo $values['itemprice']; ?></td>
                    <td><?php echo number_format($values['itemquantity']* $values['itemprice'],2); ?></td>
                    <td><a href="index.php?action=delete&id=<?php echo $values['itemid']; ?>"><span class="text-danger">Remove</span></a> </td>
                </tr>
                <?php

                      $total = $total + ($values['itemquantity']*$values['itemprice']);

                        }  ?>
                        <tr>
                            <td colspan="3" align="right">Total</td>
                            <td align="right">$ <?php echo number_format($total,2);?> </td>
                        </tr>
                <?php
                    }
            //    var_dump($_SESSION['shoppingcart']);
                    ?>


            </table>
        </div>
    </div>
</body>
</html>