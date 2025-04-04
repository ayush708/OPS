<?php
session_start();
include('config/constants.php');

// Check if item_id is set
if (isset($_GET['item_id'])) {
    $item_id = $_GET['item_id'];

    // Loop through cart to remove the item
    foreach ($_SESSION['cart'] as $key => $item) {
        if ($item['id'] == $item_id) {
            unset($_SESSION['cart'][$key]);
            $_SESSION['order'] = "Item removed from cart successfully.";
            break;
        }
    }

    // Redirect to cart page
    header('Location: cart.php');
    exit();
} else {
    // Redirect to cart page if item_id is not set
    header('Location: cart.php');
    exit();
}
?>
