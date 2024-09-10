<?php
include('config/constants.php'); // Ensure this path is correct for your project

// Get the parameters from eSewa
$amount = $_POST['amt'];
$pid = $_POST['pid'];
$scd = $_POST['scd'];
$txnid = $_POST['txnid'];

// Verify the transaction and update your order status in the database
// ...

// Redirect or show a success message
echo "<h1>Payment Successful</h1>";
// Optionally, redirect to a thank you page or order details page
?>
