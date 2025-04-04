<?php
    //check wether the user is logged in or not
    //authorization access control
    if(!isset($_SESSION['user'])) //is user session is not set
    {
        //user is not logged in
       
        $_SESSION['no-login-msg'] = "<div class='text-center'>Please login to access admin pannel</div>";
         //redirect to login page
         header('location:'.SITEURL.'admin/login.php');

    }
    
?>