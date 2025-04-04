<?php
session_start();

// Check if the quantity and item_id are set
if (isset($_POST['quantity']) && isset($_POST['item_id'])) {
    $item_id = $_POST['item_id'];
    $quantity = $_POST['quantity'];

    // Update the quantity of the item in the session
    foreach ($_SESSION['cart'] as $key => $item) {
        if ($item['id'] == $item_id) {
            $_SESSION['cart'][$key]['quantity'] = $quantity;
            break;
        }
    }

    // Redirect back to the cart page
    header('Location: cart.php');
    exit();
}
?>
