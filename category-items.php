<?php include('partials-front/menu.php');?>

<?php
    // Check whether id is passed or not
    if(isset($_GET['category_id'])) {
        // Category id is set and get the id
        $category_id = $_GET['category_id'];
        // Get category title based on category id
        $sql = "SELECT title FROM tbl_category WHERE id=$category_id";

        // Execute
        $res = mysqli_query($conn, $sql);

        // Get value from db
        $row = mysqli_fetch_assoc($res);
        // Get the title
        $category_title = $row['title'];
    } else {
        // Category not passed
        // Redirect
        header('location:'.SITEURL);
    }
?>

<!-- Item Search Section Starts Here -->
<section class="search text-center">
    <div class="container">
        <h2>Items in <a href="#" class="text-white">"<?php echo $category_title ?>"</a></h2>
    </div>
</section>
<!-- Item Search Section Ends Here -->

<!-- Item Menu Section Starts Here -->
<section class="item-menu">
    <div class="container">
        <h2 class="text-center">Item Menu</h2>

        <div class="explore-grid">
        <?php
            // Create SQL query to get items based on selected category
            $sql2 = "SELECT * FROM tbl_items WHERE category_id=$category_id";

            // Execute
            $res2 = mysqli_query($conn, $sql2);

            // Count rows
            $count2 = mysqli_num_rows($res2);

            // Check whether item is available or not
            if($count2 > 0) {
                // Item available
                while($row2 = mysqli_fetch_assoc($res2)) {
                    $id = $row2['id'];
                    $title = $row2['title'];
                    $price = $row2['price'];
                    $description = $row2['description'];
                    $image_name = $row2['image_name'];
                    ?>

                    <div class="explore-box">
                        <div class="explore-menu-img">
                            <?php
                            if($image_name == "") {
                                // Image not available
                                echo "<div class='error'>Image not available</div>";
                            } else {
                                // Image available
                                ?>
                                <img src="<?php echo SITEURL; ?>images/item/<?php echo $image_name; ?>" alt="<?php echo $title; ?>" class="img-responsive">
                                <?php
                            }
                            ?>
                        </div>

                        <div class="explore-menu-desc">
                            <h4><?php echo $title; ?></h4>
                            <p class="item-price">Rs. <?php echo $price; ?></p>
                            <p class="item-detail"><?php echo $description; ?></p>
                            <br>
                            <a href="<?php echo SITEURL; ?>order.php?item_id=<?php echo $id; ?>" class="btn btn-primary">Order Now</a>
                        </div>
                    </div>

                    <?php
                }
            } else {
                // Item not available
                echo "<div class='error'>Item not available</div>";
            }
        ?>
        </div>

        <div class="clearfix"></div>
    </div>
</section>
<!-- Item Menu Section Ends Here -->

<?php include('partials-front/footer.php');?>
