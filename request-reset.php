<?php
include('partials-front/menu.php');

// Initialize error and success variables
$err = [];
$success_message = "";

// Database connection
$conn = mysqli_connect('localhost', 'root', '', 'petshop');

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if (isset($_POST['submit'])) {
    $email = trim($_POST['email']);

    if (empty($email)) {
        $err['email'] = "Email address is required.";
    } else {
        // Check if email exists in the database
        $sql = "SELECT * FROM tbl_users WHERE email=?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $res = mysqli_stmt_get_result($stmt);

        if ($res && mysqli_num_rows($res) > 0) {
            // Generate a new unique token and expiry time (1 minute from now)
            $reset_token = bin2hex(random_bytes(32));
            $reset_expiry = date('Y-m-d H:i:s', strtotime('+1 minute'));

            // Store the new token and expiry in the database
            $sql = "UPDATE tbl_users SET reset_token=?, reset_expiry=? WHERE email=?";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "sss", $reset_token, $reset_expiry, $email);
            mysqli_stmt_execute($stmt);

            // Send reset link to the user's email
            $reset_link = "http://localhost/OPS/reset-password.php?token=" . $reset_token;
            $subject = "Password Reset Request";
            $message = "Please click on the following link to reset your password (link expires in 1 minute): " . $reset_link;
            $headers = "From: no-reply@example.com\r\n";
            mail($email, $subject, $message, $headers);

            $success_message = "A password reset link has been sent to your email address.";
        } else {
            $err['email'] = "No account found with that email address.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Request Password Reset</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="main">
        <div class="wrapper">
            <h1 class="text-center">Request Password Reset</h1>
            <br><br>

            <?php 
            if (!empty($success_message)) {
                echo "<div class='success'>$success_message</div>";
            }
            ?>
            <?php 
            if (isset($err['email'])) {
                echo "<div class='error'>{$err['email']}</div>";
            }
            ?>

            <form action="" method="POST">
                <label for="email">Email Address:</label>
                <input type="email" name="email" id="email" placeholder="Enter your email address" required>
                <input type="submit" name="submit" value="Send Reset Link">
            </form>
        </div>
    </div>
</body>
</html>
