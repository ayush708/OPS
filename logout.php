<?php
    include('config/constants.php');
    //destroy session 
    session_destroy(); //unset $_SESSION['user']
    //redirect to index page
    header('location:'.SITEURL.'index.php');
?>