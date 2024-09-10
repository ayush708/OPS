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

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    // Check if token exists in the database
    $sql = "SELECT * FROM tbl_users WHERE reset_token=?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $token);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);

    if ($res && mysqli_num_rows($res) > 0) {
        $row = mysqli_fetch_assoc($res);

        $expiry = $row['reset_expiry'];
        $current_time = date('Y-m-d H:i:s');

        // Validate the token expiry time
        if ($current_time <= $expiry) {
            // Token is valid, process the password reset form submission
            if (isset($_POST['submit'])) {
                $new_password = trim($_POST['password']);
                $confirm_password = trim($_POST['confirm_password']);

                if (empty($new_password) || empty($confirm_password)) {
                    $err['password'] = "Both fields are required.";
                } elseif ($new_password !== $confirm_password) {
                    $err['password'] = "Passwords do not match.";
                } else {
                    // Update the user's password in the database
                    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                    $sql = "UPDATE tbl_users SET password=?, reset_token=NULL, reset_expiry=NULL WHERE reset_token=?";
                    $stmt = mysqli_prepare($conn, $sql);
                    mysqli_stmt_bind_param($stmt, "ss", $hashed_password, $token);
                    mysqli_stmt_execute($stmt);

                    // Delete the token after the password is reset
                    $sql = "UPDATE tbl_users SET reset_token=NULL, reset_expiry=NULL WHERE reset_token=?";
                    $stmt = mysqli_prepare($conn, $sql);
                    mysqli_stmt_bind_param($stmt, "s", $token);
                    mysqli_stmt_execute($stmt);

                    $success_message = "Your password has been successfully reset.";
                }
            }
        } else {
            $err['token'] = "The password reset link has expired. Please request a new one.";
        }
    } else {
        $err['token'] = "Invalid or expired token.";
    }
} else {
    $err['token'] = "No token provided.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        /* Additional CSS for the form */
        .main {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f2f2f2;
        }

        .wrapper {
            background-color: white;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            width: 100%;
        }

        h1 {
            font-size: 24px;
            margin-bottom: 1.5rem;
            color: #333;
            text-align: center;
        }

        label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: bold;
            color: #555;
        }

        input[type="password"] {
            width: 100%;
            padding: 0.75rem;
            margin-bottom: 1rem;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }

        input[type="submit"] {
            width: 100%;
            padding: 0.75rem;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
        }

        .error {
            color: red;
            margin-bottom: 1rem;
            text-align: center;
        }

        .success {
            color: green;
            margin-bottom: 1rem;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="main">
        <div class="wrapper">
            <h1>Reset Password</h1>

            <?php 
            if (!empty($success_message)) {
                echo "<div class='success'>$success_message</div>";
            }
            ?>
            <?php 
            if (isset($err['token'])) {
                echo "<div class='error'>{$err['token']}</div>";
            }
            ?>
            <?php 
            if (isset($err['password'])) {
                echo "<div class='error'>{$err['password']}</div>";
            }
            ?>

            <form action="" method="POST">
                <label for="password">New Password:</label>
                <input type="password" name="password" id="password" placeholder="Enter your new password" required>
                <label for="confirm_password">Confirm Password:</label>
                <input type="password" name="confirm_password" id="confirm_password" placeholder="Confirm your new password" required>
                <input type="submit" name="submit" value="Reset Password">
            </form>
        </div>
    </div>
</body>
</html>
