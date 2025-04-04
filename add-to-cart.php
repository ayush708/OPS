<?php
// Start the session
session_start();

// Include database connection
include('config/constants.php');

// Check if item_id is set in the URL
if (isset($_GET['item_id'])) {
    $item_id = $_GET['item_id'];

    // Fetch item details from the database
    $sql = "SELECT * FROM tbl_items WHERE id='$item_id'";
    $res = mysqli_query($conn, $sql);
    
    // If query is successful
    if ($res == true) {
        // Fetch the item details
        $row = mysqli_fetch_assoc($res);
        $title = $row['title'];
        $price = $row['price'];
        $image_name = $row['image_name'];

        // Initialize cart session if not already done
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = array();
        }

        // Check if item is already in the cart
        $item_array_id = array_column($_SESSION['cart'], 'id');
        if (!in_array($item_id, $item_array_id)) {
            // Create a new item array
            $item_array = array(
                'id' => $item_id,
                'title' => $title,
                'price' => $price,
                'image_name' => $image_name,
                'quantity' => 1
            );
            // Add the item to the cart session
            $_SESSION['cart'][] = $item_array;
            $_SESSION['order'] = "Item added to cart successfully.";
        } else {
            // Increase the quantity if the item is already in the cart
            foreach ($_SESSION['cart'] as &$item) {
                if ($item['id'] == $item_id) {
                    $item['quantity'] += 1;
                    $_SESSION['order'] = "Item quantity updated in cart.";
                    break;
                }
            }
        }
    }

    // Redirect to the previous page or cart page
    header('Location: index.php'); // You can change this to your desired page
    exit();
} else {
    header('Location: index.php');
    exit();
}
?>
