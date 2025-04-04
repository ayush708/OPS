<?php
// Start session if it's not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include 'config/constants.php'; // Include the database connection

// Check if the order ID is passed via GET or session
$order_id = $_GET['order_id'] ?? $_SESSION['order_id'] ?? null;

if (!$order_id) {
    die("Order ID is missing. Please ensure the order ID is passed correctly.");
}

// Fetch the order details from the database using the correct column 'id'
$query = "SELECT * FROM tbl_order WHERE id = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $order_id);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);
$order = mysqli_fetch_assoc($result);

if ($order) {
    // Order details
    $item = $order['item'];
    $amount = $order['total']; // Assuming 'total' field in your order table represents the total amount
    $customer_name = $order['customer_name'];
    $customer_email = $order['customer_email'];
    $customer_phone = $order['customer_contact'];
    $customer_address = $order['customer_address'];
} else {
    die("Order not found.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Arial', sans-serif;
        }
    </style>
</head>
<body class="bg-gray-100">

    <div class="max-w-4xl mx-auto py-8">
        <div class="bg-white shadow-lg rounded-lg p-6">
            <h2 class="text-3xl font-semibold mb-6 text-center text-gray-800">Checkout</h2>
            <p class="text-lg mb-6 text-center text-gray-700"><strong>Item:</strong> <?php echo htmlspecialchars($item); ?></p>
            <p class="text-xl font-bold mb-6 text-center text-green-600"><strong>Amount to Pay:</strong> <?php echo htmlspecialchars($amount); ?> NPR</p>

            <form action="payment-request.php" method="POST" class="space-y-6">
                <!-- Hidden fields for payment details -->
                <input type="hidden" name="inputAmount4" value="<?php echo htmlspecialchars($amount); ?>">
                <input type="hidden" name="inputPurchasedOrderId4" value="<?php echo htmlspecialchars($order_id); ?>">
                <input type="hidden" name="inputPurchasedOrderName" value="<?php echo htmlspecialchars($item); ?>">

                <!-- Customer Name -->
                <div>
                    <label for="inputName" class="block text-sm font-medium text-gray-700">Name:</label>
                    <input type="text" id="inputName" name="inputName" value="<?php echo htmlspecialchars($customer_name); ?>" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500">
                </div>

                <!-- Customer Email -->
                <div>
                    <label for="inputEmail" class="block text-sm font-medium text-gray-700">Email:</label>
                    <input type="email" id="inputEmail" name="inputEmail" value="<?php echo htmlspecialchars($customer_email); ?>" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500">
                </div>

                <!-- Customer Phone -->
                <div>
                    <label for="inputPhone" class="block text-sm font-medium text-gray-700">Phone:</label>
                    <input type="text" id="inputPhone" name="inputPhone" value="<?php echo htmlspecialchars($customer_phone); ?>" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500">
                </div>

                <!-- Customer Address -->
                <div>
                    <label for="inputAddress" class="block text-sm font-medium text-gray-700">Address:</label>
                    <textarea id="inputAddress" name="inputAddress" rows="4" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500"><?php echo htmlspecialchars($customer_address); ?></textarea>
                </div>

                <!-- Submit Button -->
                <div class="text-center">
                    <input type="submit" name="submit" value="Pay with Khalti" class="bg-green-500 text-white px-6 py-3 rounded-md cursor-pointer hover:bg-green-600 focus:ring-4 focus:ring-green-300 focus:outline-none">
                </div>
            </form>
        </div>
    </div>

</body>
</html>
