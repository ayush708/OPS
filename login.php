<?php
include('partials-front/menu.php');

// Check login status
if (isset($_SESSION["user_logged_in"]) && $_SESSION["user_logged_in"] === true) {
    header("location: user-dashboard.php");
    exit();
}

// Initialize error array
$err = [];

// Check whether the submit button is clicked or not
if(isset($_POST['submit'])) {
    // Get data from login form and check if empty
    if(isset($_POST['username']) && !empty(trim($_POST['username']))) {
        $username = trim($_POST['username']);
    } else {
        $err['username'] = "Enter username";
    }

    if(isset($_POST['password']) && !empty($_POST['password'])) {
        $password = $_POST['password'];
    } else {
        $err['password'] = "Enter password";
    }

    if(empty($err)) {
        // SQL to check whether user with username exists or not
        $sql = "SELECT * FROM tbl_users WHERE username=?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "s", $username);
        mysqli_stmt_execute($stmt);
        $res = mysqli_stmt_get_result($stmt);

        if ($res) {
            $row = mysqli_fetch_assoc($res);
            if ($row) {
                // Verify password
                if (password_verify($password, $row['password'])) {
                    // Password matches, proceed with login
                    $_SESSION['login'] = "Login Successful";
                    $_SESSION['user'] = $username;
                    $_SESSION['user_id'] = $row['user_id'];
                    $_SESSION['full_name'] = $row['full_name'];
                    $_SESSION['user_logged_in'] = true; // Set session variable for logged in status
                    // Redirect to home page 
                    header('Location: ' . SITEURL . 'user-dashboard.php');
                    exit;
                } else {
                    // Password does not match
                    $_SESSION['login'] = "<span class='error text-center'>Username or Password didn't Match</span>";
                    // Redirect to login page 
                    header('Location: ' . SITEURL . 'login.php');
                    exit;
                }
            } else {
                // User not found
                $_SESSION['login'] = "<span class='error text-center'>Username or Password didn't Match</span>";
                // Redirect to login page 
                header('Location: ' . SITEURL . 'login.php');
                exit;
            }
        } else {
            // SQL query execution failed
            $_SESSION['login'] = "Database error: Unable to execute query.";
            header('Location: ' . SITEURL . 'login.php');
            exit;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="main">
        <div class="wrapper">
            <h1 class="text-center">Login</h1>
            <br><br>

            <!-- Error message display -->
            <?php 
            if(isset($_SESSION['login'])) {
                echo $_SESSION['login'];
                unset($_SESSION['login']);
            }
            ?>
            <br><br>

            <!-- Login form starts here -->
            <form action="" method="POST" onsubmit="return validateLoginForm()" style="width: 40%; margin: 100px auto; padding: 30px; border: 2px solid #2196F3; border-radius: 10px; background-color: #ffffff; box-shadow: 0 2px 5px rgba(0,0,0,0.3);">
                <h2 style="text-align: center; color: #2196F3; margin-bottom: 20px;">User Login</h2>
                
                <div style="margin-bottom: 15px;">
                    <label for="username" style="display: inline-block; width: 100px; font-weight: bold;">Username:</label>
                    <input type="text" name="username" id="username" placeholder="Enter Username" value="<?php if(isset($username)) echo $username; ?>" style="width: calc(100% - 120px); padding: 10px; border: 1px solid #ccc; border-radius: 5px;">
                    <br>
                    <span class="error" style="color: red; font-size: 0.9em;">
                        <?php if(isset($err['username'])) echo $err['username']; ?>
                    </span>
                </div>

                <div style="margin-bottom: 20px;">
                    <label for="password" style="display: inline-block; width: 100px; font-weight: bold;">Password:</label>
                    <input type="password" name="password" id="password" placeholder="Enter Password" style="width: calc(100% - 120px); padding: 10px; border: 1px solid #ccc; border-radius: 5px;">
                    <br>
                    <span class="error" style="color: red; font-size: 0.9em;">
                        <?php if(isset($err['password'])) echo $err['password']; ?>
                    </span>
                </div>

                <div style="text-align: center; margin-bottom: 15px;">
                    <input type="submit" name="submit" value="Login" style="width: 100%; padding: 12px; background-color: #2196F3; color: white; border: none; border-radius: 5px; font-size: 16px; cursor: pointer;">
                </div>

                <div style="text-align: center;">
                    <span style="font-size: 0.9em;">Don't have an account? 
                        <a href="register.php" style="color: #2196F3; text-decoration: none; font-weight: bold;">Sign Up</a>
                    </span>
                    <br><br>
                    <span style="font-size: 0.9em;">
                        <a href="forgot-password.php" style="color: #2196F3; text-decoration: none; font-weight: bold;">Forgot Password?</a>
                    </span>
                </div>
            </form>

            <!-- Login form ends here -->
        </div>
    </div>

    <?php include('partials-front/footer.php'); ?>
</body>
</html>
