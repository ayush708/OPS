<?php include('partials-front/menu.php');?>

<!-- Search section start -->
<section class="search text-center">
    <div class="container">
        <form action="<?php echo SITEURL; ?>item-search.php" method="POST">
            <input type="search" name="search" placeholder="Search for items..." class="input-search">
            <input type="submit" name="submit" value="Search" class="btn btn-primary">
       </form>
    </div>
</section>
<!-- Search section end -->

<?php
if(isset($_SESSION['order']))
{
    echo '<div class="success">' . $_SESSION['order'] . '</div>';
    unset($_SESSION['order']);
}
?>

<!-- Explore section start -->
<section class="explore">
    <div class="container">
        <h2 class="text-center">Explore Items</h2>

        <div class="explore-grid">
        <?php
        // Getting items from db that are active & featured
        $sql2 = "SELECT * FROM tbl_items WHERE active='Yes' AND featured='Yes' LIMIT 50";
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
                $quantity = $row['quantity']; // Fetch quantity

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
                        <p class="price">Rs.<?php echo $price; ?></p>
                        <p class="desc"><?php echo $description; ?></p>
                        <p class="quantity">Items Left: <?php echo $quantity; ?></p> <!-- Display quantity -->
                        <a href="<?php echo SITEURL; ?>order.php?item_id=<?php echo $id; ?>" class="btn btn-primary">Order Now</a>
                        <a href="<?php echo SITEURL; ?>add-to-cart.php?item_id=<?php echo $id; ?>" class="btn btn-secondary add-to-cart">
                            <i class="fas fa-shopping-basket"></i> Add to Cart
                        </a>
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
<!-- Explore section end -->


<?php include('partials-front/footer.php');?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
<style>
    .quantity {
    display: inline-block;
    font-weight: bold;
    color: #ff6b6b; /* Red shade for emphasis */
    background-color: #f9f9f9;
    border: 1px solid #ff6b6b; /* Matching border color */
    border-radius: 5px;
    padding: 5px 10px;
    margin-top: 10px;
    font-size: 0.9em;
}

</style>
