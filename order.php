<?php include('partials-front/menu.php'); ?>

<?php
// Check if user is logged in
if (!isset($_SESSION["user_logged_in"]) || $_SESSION["user_logged_in"] !== true) {
    header("location: login.php");
    exit();
}

// Check whether item_id is set or not
if(isset($_GET['item_id']))
{
    // Get the item id and details of the selected item
    $item_id = $_GET['item_id'];

    // Get the details for the selected food
    $sql = "SELECT * FROM tbl_items WHERE id=?";
    // Prepare statement
    $stmt = mysqli_prepare($conn, $sql);
    // Bind parameters
    mysqli_stmt_bind_param($stmt, "i", $item_id);
    // Execute
    mysqli_stmt_execute($stmt);

    // Get result
    $res = mysqli_stmt_get_result($stmt);

    // Count rows
    $count = mysqli_num_rows($res);
    // Check whether data is available or not
    if($count == 1)
    {
        // We have data
        // Get data from db
        $row = mysqli_fetch_assoc($res);

        $title = $row['title'];
        $price = $row['price'];
        $image_name = $row['image_name'];
    }
    else
    {
        // Item not available
        // Redirect
        header('location:'.SITEURL);
        exit();
    }
}
else
{
    // Redirect to home page
    header('location:'.SITEURL);
    exit();
}

// Initialize error array
$err = [];

// Check whether submit button is clicked or not
if(isset($_POST['submit']))
{
    // Get all the details from the form
    $item = $_POST['item'];
    $price = $_POST['price'];
    $qty = $_POST['qty'];
    $total = $price * $qty; // total = price x qty
    $order_date = date("Y-m-d H:i:s"); // Order date
    $status = "Ordered"; // Status
    $customer_name = $_SESSION['full_name'];
    $customer_contact = $_POST['contact'];
    $customer_email = $_POST['email'];
    $customer_address = $_POST['address'];
    $uid = $_SESSION['user_id'];

    // Validate quantity
    if(empty($qty) || !is_numeric($qty) || $qty <= 0) {
        $err['quantity'] = "Quantity must be greater than zero.";
    }

    // Validate email
    if(empty($customer_email)){
        $err['customer_email'] = "Email is required";
    } elseif(!filter_var($customer_email, FILTER_VALIDATE_EMAIL)) {
        $err['customer_email'] = "Enter valid email";
    }

    // Validate contact number
    if(empty($customer_contact)) {
        $err['customer_contact'] = "Contact is required";
    } elseif(!preg_match("/^9[0-9]{9}$/", $customer_contact)) {
        $err['customer_contact'] = "Invalid phone number";
    }

    // Validate address
    if(empty($customer_address)){
        $err['customer_address'] = "Address is required";
    }

    // Save the order in the database
    // Create SQL to save data
    if(empty($err))
    {
        $sql2 = "INSERT INTO tbl_order (item, price, qty, total, order_date, status, customer_name, customer_contact, customer_email, customer_address, uid)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        // Prepare statement
        $stmt2 = mysqli_prepare($conn, $sql2);

        if ($stmt2) {
            // Bind parameters
            mysqli_stmt_bind_param($stmt2, "siddssssssi", $item, $price, $qty, $total, $order_date, $status, $customer_name, $customer_contact, $customer_email, $customer_address, $uid);

            // Execute the query
            if(mysqli_stmt_execute($stmt2))
            {
                // Query executed and order saved
                $_SESSION['order'] = "<div class='success text-center'>Order Placed Successfully</div>";
                header('location:'.SITEURL);
                exit();
            }
            else
            {
                // Failed to save order
                $_SESSION['order'] = "<div class='error text-center'>Failed to Place Order</div>";
                header('location:'.SITEURL);
                exit();
            }
        } else {
            // Statement preparation failed
            $_SESSION['order'] = "<div class='error text-center'>Database error: Unable to prepare statement.</div>";
            header('location:'.SITEURL);
            exit();
        }
    }
}
?>

<!-- item SEARCH Section Starts Here -->
<section class="search">
    <div class="container">
        
        <h2 class="text-center text-white">Fill this form to confirm your order.</h2>

        <?php if(!empty($err)): ?>
            <div class="error">
                <?php foreach($err as $error): ?>
                    <p><?php echo $error; ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?> 

        <form action="" method="POST" class="order">
            <div class="explore-menu-img">
                <?php
                    // Check whether image is available or not
                    if($image_name == "")
                    {
                        // Image not available
                        echo "<div class='error'>Image not Available</div>";
                    }
                    else
                    {
                        // Image available
                        ?>
                        <img src="<?php echo SITEURL; ?>images/item/<?php echo $image_name; ?>" alt="" class="img-responsive img-curve">
                        <?php
                    }
                ?>
            </div>
            <div class="box">
                
                <div>

                    <h2 class="order-label">Selected item</h2>

                    <h3 class="order-label"><?php echo $title; ?></h3>

                    <input type="hidden" name="item" value="<?php echo $title; ?>">
                    <input type="hidden" name="price" value="<?php echo $price; ?>">

                    <p class="item-price order-label">$<?php echo $price; ?></p>

                    <div class="order-label">Quantity</div>
                    <input type="number" name="qty" class="input-responsive" value="1">
                </div>
                <br><br>
                
                <div>
                    <h2 class="order-label">Delivery Details</h2 >
                    
                    <div class="order-label">Phone Number</div>
                    <input type="tel" name="contact" placeholder="E.g. 9843xxxxxx" class="input-responsive">
                    
                    <div class="order-label">Email</div>
                    <input type="text" name="email" placeholder="E.g. hi@gmail.com" class="input-responsive">
                    
                    <div class="order-label">Address</div>
                    <textarea name="address" rows="10" placeholder="E.g. Street, City, Country" class="input-responsive"></textarea>
                    <br>
                    
                    <input type="submit" name="submit" value="Confirm Order" class="btn btn-primary">
                </div>
            </div>
        </form>
        
    </div>
</section>
<!-- item SEARCH Section Ends Here -->

<?php include('partials-front/footer.php'); ?>
