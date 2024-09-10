<?php
include('config/constants.php'); // Ensure this path is correct for your project

// Get the parameters from eSewa
$amount = $_POST['amt'];
$pid = $_POST['pid'];
$scd = $_POST['scd'];
$txnid = $_POST['txnid'];

// Handle the failure response
// ...

// Redirect or show a failure message
echo "<h1>Payment Failed</h1>";
// Optionally, redirect to a retry page or order details page
?>
