<?php include('config/constants.php');?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <!--imp to make website responsive-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Tech</title>

    <!-- link css-->
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/admin.css">

</head>
<body>
    <!--Navbar section start-->
    <section class="navbar">
        <div class="container">
            <div class="logo">
                <a href="<?php echo SITEURL; ?>"><img src="images/logo .png" alt="E-Tech logo" class="img-responsive"></a>
            </div>

            <div class="menu text-right">
                <ul>
                    <li>
                        <a href="<?php echo SITEURL; ?>">Home</a>
                    </li>
                    <!-- Show menu according to login status -->
                    <?php if (isset($_SESSION["user_logged_in"]) && $_SESSION["user_logged_in"] === true) : ?>
                        <li>
                            <a href="<?php echo SITEURL; ?>categories.php">Categories</a>
                        </li>
                        <li>
                            <a href="<?php echo SITEURL; ?>items.php">Items</a>
                        </li>

                        <li>
                            <a href="<?php echo SITEURL; ?>user-dashboard.php">Dashboard</a>
                        </li>

                        <li>
                            <a href="<?php echo SITEURL; ?>logout.php">Logout</a>
                        </li>
                    <?php else: ?>
                        <li>
                            <a href="<?php echo SITEURL; ?>register.php">Sign Up</a>
                        </li>
                        <li>
                            <a href="<?php echo SITEURL; ?>login.php">Login</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
            
            <div class="clearfix">

            </div>
           
        </div>
        
    </section>

    <!--Navbar section end-->