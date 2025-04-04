<?php
session_start();
include('config/constants.php'); // Include your database connection and other constants

// payment response handler
if (isset($_GET['amt']) && isset($_GET['refId']) && isset($_GET['oid'])) {
    // Fetch data from the URL
    $amount = $_GET['amt'];
    $refId = $_GET['refId'];
    $order_id = $_GET['oid'];
    
    // Validate payment amount with the actual order amount
    $sql = "SELECT * FROM tbl_order WHERE id=? AND total=?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "id", $order_id, $amount);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);

    if ($res && mysqli_num_rows($res) == 1) {
        // Fetch order details
        $row = mysqli_fetch_assoc($res);
        $status = $row['status'];

        if ($status == 'Ordered') {
            // Update order status to "Paid"
            $sql_update = "UPDATE tbl_order SET status='Paid', payment_ref=? WHERE id=?";
            $stmt_update = mysqli_prepare($conn, $sql_update);
            mysqli_stmt_bind_param($stmt_update, 'si', $refId, $order_id);

            if (mysqli_stmt_execute($stmt_update)) {
                // Success message after successful payment
                $_SESSION['order'] = "<div class='success text-center'>Payment Successful. Order ID: $order_id</div>";

                // Redirect to the provided Khalti URL
                $khalti_url = 'https://test-pay.khalti.com/wallet?pidx=hmFHtX2tuW9K3KUy46VPdc';
                header("Location: $khalti_url");
                exit();
            } else {
                // If payment update fails
                $_SESSION['order'] = "<div class='error text-center'>Failed to update payment status.</div>";
                header('location:' . SITEURL . 'index.php');
                exit();
            }
        } else {
            // If order is already paid or canceled
            $_SESSION['order'] = "<div class='error text-center'>Order is already processed or canceled.</div>";
            header('location:' . SITEURL . 'index.php');
            exit();
        }
    } else {
        // Invalid payment amount or order not found
        $_SESSION['order'] = "<div class='error text-center'>Invalid payment or order not found.</div>";
        header('location:' . SITEURL . 'index.php');
        exit();
    }
} else {
    // If required payment details are missing
    $_SESSION['order'] = "<div class='error text-center'>Invalid payment request.</div>";
    header('location:' . SITEURL . 'index.php');
    exit();
}
?>
