<?php include('partials-front/menu.php'); ?>

<!-- Search section start -->
<section class="search text-center">
    <div class="container">
        <form action="<?php echo SITEURL; ?>item-search.php" method="POST">
            <input type="search" name="search" placeholder="Search..." aria-label="Search">
            <input type="submit" name="submit" value="Search" class="btn btn-primary">
        </form>
    </div>
</section>
<!-- Search section end -->

<?php
if(isset($_SESSION['order'])) {
    echo "<div class='order-message'>".$_SESSION['order']."</div>";
    unset($_SESSION['order']);
}
?>

<!-- Categories section start -->
<section class="categories">
    <div class="container">
        <h2 class="text-center">Categories</h2>

        <?php 
            // Create SQL query to display categories from db
            $sql = "SELECT * FROM tbl_category WHERE active='Yes' AND featured='Yes'";
            // Execute
            $res = mysqli_query($conn, $sql);
            // Count rows to check whether the category is available or not
            $count = mysqli_num_rows($res);

            if($count > 0) {
                // Category available
                while($row = mysqli_fetch_assoc($res)) {
                    // Get values 
                    $id = $row['id'];
                    $title = $row['title'];
                    $image_name = $row['image_name'];
                    ?>

                    <a href="<?php echo SITEURL; ?>category-items.php?category_id=<?php echo $id; ?>" class="category-link">
                        <div class="box-3">
                            <?php
                                // Check whether image is available or not
                                if($image_name == "") {
                                    // Display message
                                    echo "<div class='error'>Image not available</div>";
                                } else {
                                    // Image available
                                    ?>
                                    <img src="<?php echo SITEURL;?>images/category/<?php echo $image_name;?>" alt="<?php echo $title; ?>" class="img-responsive">
                                    <?php
                                }
                            ?>
                            <h3 class="category-title"><?php echo $title; ?></h3>
                        </div>
                    </a>
                    
                    <?php
                }
            } else {
                // Category not available
                echo "<div class='error'>Category not added</div>";
            }
        ?>

        <div class="clearfix"></div>
    </div>
</section>
<!-- Categories section end -->

<!-- Explore section start -->
<section class="explore">
    <div class="container">
        <h2 class="text-center">Explore Items</h2>

        <div class="explore-grid">
            <?php
            // Getting items from db that are active & featured
            $sql2 = "SELECT * FROM tbl_items WHERE active='Yes' AND featured='Yes' LIMIT 6";
            // Execute
            $res2 = mysqli_query($conn, $sql2);
            // Count rows
            $count2 = mysqli_num_rows($res2);

            if($count2 > 0) {
                // Item available
                while($row = mysqli_fetch_assoc($res2)) {
                    // Get values 
                    $id = $row['id'];
                    $title = $row['title'];
                    $price = $row['price'];
                    $description = $row['description'];
                    $image_name = $row['image_name'];
                    ?>

                    <div class="explore-box">
                        <div class="explore-menu-img">
                            <?php
                            // Check whether image is available or not
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
                            <p class="price">Rs. <?php echo $price; ?></p>
                            <p class="desc"><?php echo $description; ?></p>
                            <a href="<?php echo SITEURL; ?>order.php?item_id=<?php echo $id; ?>" class="btn btn-primary">Order Now</a>
                        </div>
                        <div class="clearfix"></div>
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
<!-- Explore section end -->

<?php include('partials-front/footer.php'); ?>
