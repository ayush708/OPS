<?php include('partials-front/menu.php');?>

<?php
    //check wether id is passed or not
    if(isset($_GET['category_id']))
    {
        //category id is set and get the id
        $category_id = $_GET['category_id'];
        //get category title based on category id
        $sql = "SELECT title FROM tbl_category WHERE id=$category_id";

        //execute
        $res = mysqli_query($conn, $sql);

        //get value from db
        $row = mysqli_fetch_assoc($res);
        //get the title
        $category_title = $row['title'];


    }
    else
    {
        //category no passed

        //redirect
        header('location:'.SITEURL);
    }
?>
    <!-- item sEARCH Section Starts Here -->
    <section class="search text-center">
        <div class="container">
            
            <h2>Item on <a href="#" class="text-white">"<?php echo $category_title ?>"</a></h2>

        </div>
    </section>
    <!-- item sEARCH Section Ends Here -->



    <!-- item MEnu Section Starts Here -->
    <section class="item-menu">
        <div class="container">
            <h2 class="text-center">Item Menu</h2>

            <?php
                //create sql query to get item based on selected category
                $sql2 = "SELECT * FROM tbl_items WHERE category_id=$category_id";

                //execite
                $res2 = mysqli_query($conn, $sql2);

                //count rows
                $count2 = mysqli_num_rows($res2);

                //check wether item is available or not
                if($count2>0)
                {
                    //item avi
                    while($row2=mysqli_fetch_assoc($res2))
                    {
                        $id = $row2['id'];
                        $title = $row2['title'];
                        $price = $row2['price'];
                        $description = $row2['description'];
                        $image_name = $row2['image_name'];

                        ?>

                            <div class="explore-box box-3">
                                <div class="explore-menu-img">
                                    <?php
                                        if($image_name=="")
                                        {
                                            //img not avi
                                            echo "<div class='error'>Image not available</div>";

                                        }
                                        else
                                        {
                                            //img avi
                                            ?>
                                            <img src="<?php echo SITEURL; ?>images/item/<?php echo $image_name; ?>" alt="" class="img-responsive img-curve">
                                            <?php
                                        }
                                    ?>
                                  
                                </div>

                                <div class="exploer-menu-desc">
                                    <h4><?php echo $title; ?></h4>
                                    <p class="item-price">$<?php echo $price; ?></p>
                                    <p class="item-detail">
                                        <?php echo $description; ?>
                                    </p>
                                    <br>

                                    <a href="<?php echo SITEURL; ?>order.php?item_id=<?php echo $id; ?>" class="btn btn-primary">Order Now</a>
                                </div>
                            </div>


                        <?php
                    }
                }
                else
                {
                    //item not avi
                    echo "<div class='error'>Item not available</div>";
                }
            ?>

         

            <div class="clearfix"></div>

            

        </div>

    </section>
    <!-- item Menu Section Ends Here -->

    <?php include('partials-front/footer.php');?>