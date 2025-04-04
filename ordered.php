<?php
session_start();
include('config/constants.php'); 
include('partials-front/menu.php'); 

// Check if user is logged in
if (!isset($_SESSION["user_logged_in"]) || $_SESSION["user_logged_in"] !== true) {
    header("location: login.php");
    exit();
}

// Fetch cart details from session
$cart_items = isset($_SESSION['cart']) ? $_SESSION['cart'] : array();
$total_price = 0;

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Process the order
    if (count($cart_items) > 0) {
        // Get delivery details from the form
        $full_name = mysqli_real_escape_string($conn, $_POST['full_name']);
        $phone = mysqli_real_escape_string($conn, $_POST['phone']);
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $address = mysqli_real_escape_string($conn, $_POST['address']);
        $payment_method = mysqli_real_escape_string($conn, $_POST['payment_method']);

        // Store order in the database
        foreach ($cart_items as $item) {
            // Check if $item is an array and contains the expected keys
            if (is_array($item) && isset($item['title'], $item['price'], $item['quantity'])) {
                $item_total = $item['price'] * $item['quantity'];
                $total_price += $item_total;

                // Validate user session
                if (isset($_SESSION['user']) && is_array($_SESSION['user']) && isset($_SESSION['user']['user_id'])) {
                    // Insert order into tbl_order
                    $sql = "INSERT INTO tbl_order (item, price, qty, total, order_date, status, customer_name, customer_contact, customer_email, customer_address, uid, payment_option)
                            VALUES (?, ?, ?, ?, NOW(), 'Ordered', ?, ?, ?, ?, ?, ?)";
                    $stmt = mysqli_prepare($conn, $sql);
                    mysqli_stmt_bind_param($stmt, 'sdiissss', 
                        $item['title'], 
                        $item['price'], 
                        $item['quantity'], 
                        $item_total, 
                        $full_name, 
                        $phone, 
                        $email, 
                        $address, 
                        $_SESSION['user']['user_id'], // Ensure this is set correctly
                        $payment_method
                    );
                    mysqli_stmt_execute($stmt);
                    mysqli_stmt_close($stmt);
                } else {
                    // Handle the error: user is not logged in or user_id is missing
                    echo "<div class='error text-red-600'>User session not found. Please log in again.</div>";
                    exit();
                }
            } else {
                // Handle the error: item is not in expected format
                echo "<div class='error text-red-600'>Invalid item format in cart.</div>";
                exit();
            }
        }

        // Clear the cart
        unset($_SESSION['cart']);

        // Redirect based on payment option
        if ($payment_method === 'Online Payment') {
            // For online payments, redirect to the payment request page
            header('location: payment-request.php'); 
        } else {
            // Set success message in session for Cash on Delivery
            $_SESSION['order'] = "<div class='success text-center'>Order Placed Successfully</div>";
            
            // Redirect to index.php
            header('location: index.php'); 
        }
        exit();
    } else {
        // Handle case where no valid items are in the cart
        header('location: index.php');
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <title>Order Confirmation</title>
</head>
<body>

<section class="order-confirmation py-8">
    <div class="container mx-auto">
        <h2 class="text-center text-3xl font-bold mb-6">Confirm Your Order</h2>

        <?php if (count($cart_items) > 0): ?>
            <table class="min-w-full bg-white border border-gray-200"> 
                <thead class="bg-gray-100">
                    <tr>
                        <th class="py-2 px-4 border-b border-gray-200 text-left text-sm font-semibold text-gray-600">Item</th>
                        <th class="py-2 px-4 border-b border-gray-200 text-left text-sm font-semibold text-gray-600">Price</th>
                        <th class="py-2 px-4 border-b border-gray-200 text-left text-sm font-semibold text-gray-600">Quantity</th>
                        <th class="py-2 px-4 border-b border-gray-200 text-left text-sm font-semibold text-gray-600">Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($cart_items as $item): ?>
                        <?php 
                        $item_total = $item['price'] * $item['quantity']; 
                        $total_price += $item_total;
                        ?>
                        <tr class="hover:bg-gray-50">
                            <td class="py-3 px-4 border-b border-gray-200"><?= htmlspecialchars($item['title']) ?></td>
                            <td class="py-3 px-4 border-b border-gray-200">Rs. <?= htmlspecialchars($item['price']) ?></td>
                            <td class="py-3 px-4 border-b border-gray-200"><?= htmlspecialchars($item['quantity']) ?></td>
                            <td class="py-3 px-4 border-b border-gray-200">Rs. <?= htmlspecialchars($item_total) ?></td>
                        </tr>
                    <?php endforeach; ?>
                    <tr class="font-bold">
                        <td colspan="3" class="py-3 px-4 border-b border-gray-200">Total</td>
                        <td class="py-3 px-4 border-b border-gray-200">Rs. <?= htmlspecialchars($total_price) ?></td>
                    </tr>
                </tbody>
            </table>

            <h3 class="text-xl font-semibold mt-8">Delivery Details</h3>
            <form action="" method="POST" class="mt-4">
                <div class="mb-4">
                    <label for="full_name" class="block text-gray-700 font-bold">Full Name</label>
                    <input type="text" name="full_name" id="full_name" required class="w-full p-2 border rounded" placeholder="Enter your full name">
                </div>
                <div class="mb-4">
                    <label for="phone" class="block text-gray-700 font-bold">Phone Number</label>
                    <input type="text" name="phone" id="phone" required class="w-full p-2 border rounded" placeholder="Enter your phone number">
                </div>
                <div class="mb-4">
                    <label for="email" class="block text-gray-700 font-bold">Email</label>
                    <input type="email" name="email" id="email" required class="w-full p-2 border rounded" placeholder="Enter your email">
                </div>
                <div class="mb-4">
                    <label for="address" class="block text-gray-700 font-bold">Address</label>
                    <textarea name="address" id="address" required class="w-full p-2 border rounded" placeholder="Enter your delivery address"></textarea>
                </div>

                <div class="mb-4">
                    <label for="payment_method" class="block text-gray-700 font-bold">Payment Method</label>
                    <select name="payment_method" id="payment_method" required class="w-full p-2 border rounded">
                        <option value="COD">Cash on Delivery</option>
                        <option value="Online Payment">Online Payment</option>
                    </select>
                </div>

                <input type="hidden" name="total_price" value="<?= htmlspecialchars($total_price) ?>">
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Confirm Order</button>
            </form>

        <?php else: ?>
            <div class="error text-red-600 text-lg font-bold text-center mt-4">Your cart is empty.</div>
        <?php endif; ?>

    </div>
</section>

<?php include('partials-front/footer.php'); ?>

</body>
</html>
