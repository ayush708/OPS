<?php include('partials-front/menu.php'); ?>


<?php
// Check if user is logged in
if (!isset($_SESSION["user_logged_in"]) || $_SESSION["user_logged_in"] !== true) {
    header("location: login.php");
    exit();
}
include('config/constants.php');

// Check if item_id is passed in the URL
if (isset($_GET['item_id'])) {
    $item_id = $_GET['item_id'];

    // SQL query to increment the total_views column
    $sql = "UPDATE tbl_items SET total_views = total_views + 1 WHERE id = $item_id";

    // Execute the query
    $res = mysqli_query($conn, $sql);

    // Check if the query executed successfully
    if ($res === false) {
        // Optional: Log the error or display a message
        echo "<div class='error'>Failed to update views count. Please try again.</div>";
    }
}
// Check whether item_id is set or not
if (isset($_GET['item_id'])) {
    // Get the item id and details of the selected item
    $item_id = $_GET['item_id'];

    // Get the details for the selected item
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
    if ($count == 1) {
        // We have data
        // Get data from db
        $row = mysqli_fetch_assoc($res);

        $title = $row['title'];
        $price = $row['price'];
        $image_name = $row['image_name']; // Image is not used now
        $available_qty = $row['quantity']; // Assuming the column name is `quantity`
    } else {
        // Item not available
        // Redirect
        header('location:' . SITEURL);
        exit();
    }
} else {
    // Redirect to home page
    header('location:' . SITEURL);
    exit();
}

// Initialize error array
$err = [];

// Fetch user details from database
$user_id = $_SESSION['user_id'];
$sql_user = "SELECT * FROM tbl_users WHERE user_id=?";
$stmt_user = mysqli_prepare($conn, $sql_user);
mysqli_stmt_bind_param($stmt_user, "i", $user_id);
mysqli_stmt_execute($stmt_user);
$res_user = mysqli_stmt_get_result($stmt_user);

if ($res_user && mysqli_num_rows($res_user) == 1) {
    $user_row = mysqli_fetch_assoc($res_user);
    $customer_name = $user_row['full_name'];
    $customer_contact = $user_row['phone'];
    $customer_email = $user_row['email'];
} else {
    // User details not found or multiple users found unexpectedly
    $_SESSION['order'] = "<div class='error text-center'>User details not found.</div>";
    header('location:' . SITEURL);
    exit();
}

// Check whether submit button is clicked or not
if (isset($_POST['submit'])) {
    // Get all the details from the form
    $item = $_POST['item'];
    $price = $_POST['price'];
    $qty = $_POST['qty'];
    $total = $price * $qty; // total = price x qty
    $order_date = date("Y-m-d H:i:s"); // Order date
    $status = "Ordered"; // Status
    $customer_address = $_POST['address'];
    $payment_option = $_POST['payment_option'];
    $uid = $_SESSION['user_id'];

    // Validate quantity
    if (empty($qty) || !is_numeric($qty) || $qty <= 0) {
        $err['quantity'] = "Quantity must be greater than zero.";
    } elseif ($qty > $available_qty) {
        $err['quantity'] = "Only $available_qty items available in stock.";
    }

    // Validate address
    if (empty($customer_address)) {
        $err['customer_address'] = "Address is required";
    }

// Save the order in the database
if (empty($err)) {
    $sql2 = "INSERT INTO tbl_order (item, price, qty, total, order_date, status, customer_name, customer_contact, customer_email, customer_address, payment_option, uid)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    // Prepare statement
    $stmt2 = mysqli_prepare($conn, $sql2);

    if ($stmt2) {
        // Bind parameters
        mysqli_stmt_bind_param($stmt2, 'siddsssssssi', $item, $price, $qty, $total, $order_date, $status, $customer_name, $customer_contact, $customer_email, $customer_address, $payment_option, $uid);

        // Execute the query
        if (mysqli_stmt_execute($stmt2)) {
            // Get the order ID of the last inserted row
            $order_id = mysqli_insert_id($conn);

            // Save the order ID in the session
            $_SESSION['order_id'] = $order_id;

            // Deduct ordered quantity from stock
            $new_qty = $available_qty - $qty;
            $sql_update_qty = "UPDATE tbl_items SET quantity = ? WHERE id = ?";
            $stmt_update_qty = mysqli_prepare($conn, $sql_update_qty);
            mysqli_stmt_bind_param($stmt_update_qty, 'ii', $new_qty, $item_id);
            mysqli_stmt_execute($stmt_update_qty);
            
//here total_sold is increased respect to the selling or orderd by an user
            $sql_update_sold = "UPDATE tbl_items SET total_sold = total_sold + ? WHERE id = ?";
$stmt_update_sold = mysqli_prepare($conn, $sql_update_sold);
mysqli_stmt_bind_param($stmt_update_sold, 'ii', $qty, $item_id);
mysqli_stmt_execute($stmt_update_sold);

            // Redirect based on payment method
            if ($payment_option == "Online Payment") {
                // Redirect to checkout for online payment
                header("Location: checkout.php?amount=$total&item=$item&order_id=$order_id");
                exit();
            } else {
                // Order successfully placed, show success message
                $_SESSION['order'] = "<div class='success text-center'>Order Placed Successfully</div>";
                header('location:' . SITEURL);
                exit();
            }
        } else {
            // Failed to save order
            $_SESSION['order'] = "<div class='error text-center'>Failed to Place Order</div>";
            header('location:' . SITEURL);
            exit();
        }
    } else {
        // Statement preparation failed
        $_SESSION['order'] = "<div class='error text-center'>Database error: Unable to prepare statement.</div>";
        header('location:' . SITEURL);
        exit();
    }
}

}
?>

<!-- item SEARCH Section Starts Here -->
<section class="search" style="padding: 20px; background-color: #f8f9fa;">
    <div class="container" style="max-width: 900px; margin: 0 auto; background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);">
        
        <h2 class="text-center" style="color: #343a40; margin-bottom: 20px; font-size: 1.5em;">Fill this form to confirm your order.</h2>

        <?php if (!empty($err)): ?>
            <div class="error text-center" style="color: red; margin-bottom: 20px;">
                <?php foreach ($err as $error): ?>
                    <p><?php echo $error; ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?> 

        <form action="" method="POST" class="order" style="display: flex; flex-direction: column; gap: 20px;">
            <div class="box" style="display: flex; flex-direction: column; gap: 20px;">
                <div>
                    <h2 class="order-label" style="font-size: 1.5em; color: #343a40; margin-bottom: 10px;">Selected item</h2>
                    <h3 class="order-label" style="font-size: 1.2em; color: #495057; margin-bottom: 10px;"><?php echo $title; ?></h3>
                    <input type="hidden" name="item" value="<?php echo $title; ?>">
                    <input type="hidden" name="price" value="<?php echo $price; ?>">
                    <p class="item-price order-label" style="font-size: 1.2em; color: #28a745; margin-bottom: 10px;">Rs.<?php echo $price; ?></p>
                    <div class="order-label" style="font-size: 1.2em; color: #343a40; margin-bottom: 10px;">Quantity</div>
                    <input type="number" name="qty" class="input-responsive" value="1" min="1" style="width: 100%; padding: 10px; border-radius: 5px; border: 1px solid #ced4da;">
                </div>
                
                <div>
                    <h2 class="order-label" style="font-size: 1.5em; color: #343a40; margin-bottom: 10px;">Delivery Details</h2>
                    <div class="order-label" style="font-size: 1.2em; color: #343a40; margin-bottom: 10px;">Full Name</div>
                    <input type="text" name="full_name" value="<?php echo $customer_name; ?>" readonly class="input-responsive" disabled style="width: 100%; padding: 10px; border-radius: 5px; border: 1px solid #ced4da;">
                    
                    <div class="order-label" style="font-size: 1.2em; color: #343a40; margin-bottom: 10px;">Phone Number</div>
                    <input type="tel" name="contact" value="<?php echo $customer_contact; ?>" readonly class="input-responsive" disabled style="width: 100%; padding: 10px; border-radius: 5px; border: 1px solid #ced4da;">
                    
                    <div class="order-label" style="font-size: 1.2em; color: #343a40; margin-bottom: 10px;">Email</div>
                    <input type="text" name="email" value="<?php echo $customer_email; ?>" readonly class="input-responsive" disabled style="width: 100%; padding: 10px; border-radius: 5px; border: 1px solid #ced4da;">
                    
                    <div class="order-label" style="font-size: 1.2em; color: #343a40; margin-bottom: 10px;">Address</div>
                    <textarea name="address" rows="4" placeholder="Street, City" class="input-responsive" style="width: 100%; padding: 10px; border-radius: 5px; border: 1px solid #ced4da;"></textarea>
                    
                    <div class="order-label" style="font-size: 1.2em; color: #343a40; margin-bottom: 10px;">Payment Method</div>
                    <select name="payment_option" class="input-responsive" style="width: 100%; padding: 10px; border-radius: 5px; border: 1px solid #ced4da;">
                        <option value="Cash on Delivery">Cash on Delivery</option>
                        <option value="Online Payment">Online Payment</option>
                    </select>
                </div>
            </div>

            <input type="submit" name="submit" value="Confirm Order" class="btn btn-primary" style="background-color: #007bff; color: #fff; padding: 10px; border: none; border-radius: 5px; cursor: pointer; width: 100%;">
        </form>
    </div>
</section>
<!-- item SEARCH Section Ends Here -->

<?php include('partials-front/footer.php'); ?>