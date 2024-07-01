<?php include('partials-front/menu.php');?>

<!-- item SEARCH Section Starts Here -->
<section class="search text-center">
    <div class="container">
        <?php
            // Get the search keyword
            $search = $_POST['search'];
        ?>
        <h2>Items on Your Search <a href="#" class="text-white">"<?php echo $search; ?>"</a></h2>
    </div>
</section>
<!-- item SEARCH Section Ends Here -->

<!-- item Menu Section Starts Here -->
<section class="menu">
    <div class="container">
        <h2 class="text-center">Item Menu</h2>

        <?php
        // SQL query to get items based on search
        $sql = "SELECT * FROM tbl_items WHERE title LIKE '%$search%' OR description LIKE '%$search%'";

        // Execute query
        $res = mysqli_query($conn, $sql);
        // Count rows
        $count = mysqli_num_rows($res);

        // Check whether items are available or not
        if ($count > 0) {
            // Items available
            while ($row = mysqli_fetch_assoc($res)) {
                // Get the details
                $id = $row['id'];
                $title = $row['title'];
                $price = $row['price'];
                $description = $row['description'];
                $image_name = $row['image_name'];
                ?>
                <div class="explore-box box-3">
                    <div class="explore-menu-img">
                        <?php
                            // Check whether image is available or not
                            if ($image_name == "") {
                                // Image not available
                                echo "<div class='error'>Image Not Available</div>";
                            } else {
                                // Image available
                                ?>
                                <img src="<?php echo SITEURL; ?>images/item/<?php echo $image_name; ?>" alt="<?php echo $title; ?>" class="img-responsive img-curve">
                                <?php
                            }
                        ?>
                    </div>

                    <div class="explore-menu-desc">
                        <h4><?php echo $title; ?></h4>
                        <p class="price">$<?php echo $price; ?></p>
                        <p class="item-detail">
                            <?php echo $description; ?>
                        </p>
                        <br>
                        </p>
                        <a href="<?php echo SITEURL; ?>order.php?item_id=<?php echo $id; ?>" class="btn btn-primary">Order Now</a>
                    </div>
                </div>
                <?php
            }
        } else {
            // Items not available
            echo "<div class='error'>Item Not Found</div>";
        }
        ?>

        <div class="clearfix"></div>
    </div>
</section>
<!-- item Menu Section Ends Here -->

<?php include('partials-front/footer.php'); ?>
