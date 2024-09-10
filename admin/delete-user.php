<?php

// Include constants.php file
include('../config/constants.php');

// Check if username is set in the URL
if (isset($_GET['username']) && !empty($_GET['username'])) {
    // Validate and sanitize the username
    $username = trim($_GET['username']);
    
    if (!empty($username)) {
        // Create SQL query to delete user
        $sql = "DELETE FROM tbl_users WHERE username = ?";
        
        // Prepare statement
        if ($stmt = mysqli_prepare($conn, $sql)) {
            // Bind parameters
            mysqli_stmt_bind_param($stmt, "s", $username);
            
            // Execute statement
            if (mysqli_stmt_execute($stmt)) {
                // Query executed successfully and user deleted
                $_SESSION['delete'] = "User Deleted Successfully";
                header('location:'.SITEURL.'admin/manage-user.php');
                exit;
            } else {
                // Failed to execute query
                $_SESSION['delete'] = "Failed to Delete User. Try Again";
                header('location:'.SITEURL.'admin/manage-user.php');
                exit;
            }
        } else {
            // Failed to prepare statement
            $_SESSION['delete'] = "Failed to Prepare Statement";
            header('location:'.SITEURL.'admin/manage-user.php');
            exit;
        }
    } else {
        // Invalid username
        $_SESSION['delete'] = "Invalid Username";
        header('location:'.SITEURL.'admin/manage-user.php');
        exit;
    }
} else {
    // username not set in URL
    $_SESSION['delete'] = "Username Missing";
    header('location:'.SITEURL.'admin/manage-user.php');
    exit;
}

?>
