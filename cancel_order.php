<?php
// Connect to the database
$conn = mysqli_connect("localhost", "root", "", "petshop");

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Get the order ID from the POST request
$order_id = $_POST['order_id'];

// Check if the order exists and is not delivered
$sql = "SELECT * FROM tbl_order WHERE id = $order_id AND status NOT IN ('Delivered', 'On Delivery')";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
    // Delete the order
    $sql = "DELETE FROM tbl_order WHERE id = $order_id";
    $result = mysqli_query($conn, $sql);
    if ($result) {
        echo "Order cancelled successfully.";
    } else {
        echo "Failed to cancel order: " . mysqli_error($conn);
    }
} else {
    echo "Order not found or already delivered.";
}

// Close the database connection
mysqli_close($conn);
?>