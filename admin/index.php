<?php include('partials/menu.php');?>
        <!--main content section starts here-->
        <div class="main">
            <div class="wrapper">
                <h1>Dashboard</h1>
                <br>
                <?php 
                    if(isset($_SESSION['login']))
                    {
                        echo $_SESSION['login'];
                        unset($_SESSION['login']);
                    }
                ?>
                <br>

                <div class="col-4 text-center">

                    <?php
                    //sql query
                        $sql = "SELECT * FROM tbl_category";
                        //execute query
                        $res = mysqli_query($conn, $sql);
                        //count
                        $count = mysqli_num_rows($res);

                    ?>
                    <h1><?php echo $count; ?></h1>
                    Categories
                </div>


                <div class="col-4 text-center">


                    <?php
                        //sql query
                            $sql2 = "SELECT * FROM tbl_items";
                            //execute query
                            $res2 = mysqli_query($conn, $sql2);
                            //count
                            $count2 = mysqli_num_rows($res2);

                    ?>
                    <h1><?php echo $count2; ?></h1>
                    Items
                </div>


                <div class="col-4 text-center">

                    <?php
                            //sql query
                                $sql3 = "SELECT * FROM tbl_order";
                                //execute query
                                $res3 = mysqli_query($conn, $sql3);
                                //count
                                $count3 = mysqli_num_rows($res3);

                    ?>
                    <h1><?php echo $count3; ?></h1>
                    Total Orders
                </div>


                <div class="col-4 text-center">

                <?php
                    //create sql query to get total revenue
                    //aggregate fun in sql
                    $sql4 = "SELECT SUM(total) AS Total FROM tbl_order WHERE status='Delivered'";

                    //execute
                    $res4 = mysqli_query($conn, $sql4);

                    //get value
                    $row4 = mysqli_fetch_assoc($res4);

                    //get total revenue
                    $total_revenue = $row4['Total'];

                ?>
                    <h1>Rs.<?php echo $total_revenue; ?></h1>
                    Revenue Generated
                </div>
                <div class="clearfix"></div>
            </div>
            
        </div>

        
         <!--main content section ends here-->

         <?php include('partials/footer.php')?>