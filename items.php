<?php include('partials-front/menu.php');?>

<!-- item SEARCH Section Starts Here -->
<section class="search text-center">
    <div class="container">
        
        <form action="<?php echo SITEURL; ?>item-search.php" method="POST">
            <input type="search" name="search" placeholder="Search for item.." required>
            <input type="submit" name="submit" value="Search" class="btn btn-primary">
        </form>
    </div>
</section>
<!-- item SEARCH Section Ends Here -->

<!-- item Menu Section Starts Here -->
<section class="menu">
    <div class="container">
        <h2 class="text-center">Item Menu</h2>

        <?php
            // Display items that are active
            $sql = "SELECT * FROM tbl_items WHERE active='Yes'";
            // Execute
            $res = mysqli_query($conn, $sql);

            // Count rows 
            $count = mysqli_num_rows($res);
            // Check whether items are available or not

            if($count > 0)
            {
                // Items available
                while($row = mysqli_fetch_assoc($res))
                {
                    // Get values
                    $id = $row['id'];
                    $title = $row['title'];
                    $description = $row['description'];
                    $price = $row['price'];
                    $image_name = $row['image_name'];
                    ?>

                    <div class="explore-box box-3">
                        <div class="explore-menu-img">
                            <?php
                            // Check whether image is available or not
                            if($image_name == "")
                            {
                                // Image not available
                                echo "<div class='error'>Image Not Available</div>";
                            }
                            else
                            {
                                // Image available
                                ?>
                                <img src="<?php echo SITEURL; ?>images/item/<?php echo $image_name; ?>" alt="<?php echo $title; ?>" class="img-responsive img-curve">
                                <?php
                            }
                            ?>
                        </div>

                        <div class="explore-menu-desc">
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
                // Items not available
                echo "<div class='error'>Item not Found</div>";
            }
        ?>

        <div class="clearfix"></div>
    </div>
</section>
<!-- item Menu Section Ends Here -->

<?php include('partials-front/footer.php');?>
