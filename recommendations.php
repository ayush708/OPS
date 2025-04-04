<?php
// Include the database connection file
include('config/constants.php');

// Define the function to fetch recommended items
function getRecommendedItems($conn) {
    // SQL query to get the items, ordered by popularity score
    $sql = "SELECT * FROM tbl_items WHERE active='Yes' AND featured='Yes' ORDER BY (total_sold * 1.5 + total_views * 0.5) DESC LIMIT 20";
    $res = mysqli_query($conn, $sql);
    
    // Check if the query was successful
    if ($res) {
        // Create an array to hold the fetched items
        $items = [];
        while ($row = mysqli_fetch_assoc($res)) {
            $items[] = $row;
        }
        return $items; // Return the array of items
    } else {
        // If there was an error in the query, return an empty array
        return [];
    }
}

// Include the header/menu section
include('partials-front/menu.php');
?>

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
if (isset($_SESSION['order'])) {
    echo "<div class='order-message'>" . $_SESSION['order'] . "</div>";
    unset($_SESSION['order']);
}
?>

<!-- Recommended Items section start -->
<section class="recommendation">
    <div class="container">
        <h2 class="text-center">Recommended Items</h2>

        <div class="explore-grid">
            <?php
            // Fetch recommended items based on a calculated popularity score
            $recommended_items = getRecommendedItems($conn);

            if (!empty($recommended_items)) {
                foreach ($recommended_items as $item) {
                    // Get item values
                    $id = $item['id'];
                    $title = $item['title'];
                    $price = $item['price'];
                    $image_name = $item['image_name'];
                    $quantity = $item['quantity'];
                    $total_sold = $item['total_sold']; // Number of items sold
                    $total_views = $item['total_views']; // Number of views

                    // Calculate popularity score (simple formula for now)
                    $weight_sold = 1.5;
                    $weight_views = 0.5;
                    $popularity_score = ($total_sold * $weight_sold) + ($total_views * $weight_views);

                    ?>
                    <div class="explore-box">
                        <div class="explore-menu-img">
                            <?php
                            // Check whether image is available or not
                            if ($image_name == "") {
                                echo "<div class='error'>Image not available</div>";
                            } else {
                                ?>
                                <img src="<?php echo SITEURL; ?>images/item/<?php echo $image_name; ?>" alt="<?php echo $title; ?>" class="img-responsive">
                                <?php
                            }
                            ?>
                        </div>

                        <div class="explore-menu-desc">
                            <h4><?php echo $title; ?></h4>
                            <p class="price">Rs. <?php echo $price; ?></p>
                            <p class="quantity">Items Left: <?php echo $quantity; ?></p> <!-- Display quantity -->
                            <p class="popularity-score">Popularity Score: <?php echo round($popularity_score, 2); ?></p> <!-- Display popularity score -->
                            <a href="<?php echo SITEURL; ?>order.php?item_id=<?php echo $id; ?>" class="btn btn-primary">Order Now</a>
                            <a href="<?php echo SITEURL; ?>add-to-cart.php?item_id=<?php echo $id; ?>" class="btn btn-secondary add-to-cart">
                                <i class="fas fa-shopping-basket"></i> Add to Cart
                            </a>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    <?php
                }
            } else {
                // No recommended items available
                echo "<div class='error'>No recommended items at the moment</div>";
            }
            ?>
        </div>

        <div class="clearfix"></div>
    </div>
</section>
<!-- Recommended Items section end -->

<?php include('partials-front/footer.php'); ?>
