<?php
// Include the constants file where the database connection is defined
include('config/constants.php');

// Check if a session is already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Initialize variables
$errors = array();
$successMessage = '';

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $currentPassword = $_POST['current_password'];
    $newPassword = $_POST['new_password'];
    $confirmPassword = $_POST['confirm_password'];

    // Validate input
    if (empty($currentPassword) || empty($newPassword) || empty($confirmPassword)) {
        $errors[] = 'All fields are required.';
    }

    if ($newPassword !== $confirmPassword) {
        $errors[] = 'New password and confirm password do not match.';
    }

    if (strlen($newPassword) < 8) {
        $errors[] = 'New password must be at least 8 characters long.';
    }

    // Check if the current password is correct
    if (empty($errors)) {
        $user_id = $_SESSION['user_id'];
        $query = "SELECT password FROM tbl_users WHERE user_id = $user_id";
        $result = mysqli_query($conn, $query);
        $user = mysqli_fetch_assoc($result);

        if (password_verify($currentPassword, $user['password'])) {
            // Update the password
            $newPasswordHashed = password_hash($newPassword, PASSWORD_DEFAULT);
            $query = "UPDATE tbl_users SET password = '$newPasswordHashed' WHERE user_id = $user_id";
            $result = mysqli_query($conn, $query);

            if ($result) {
                // Store success message in session and log out user
                $_SESSION['password_change_success'] = 'Password updated successfully. Please log in again.';
                
                // Clear the session data
                session_unset();
                session_destroy();

                // Redirect to login page
                header('Location: login.php');
                exit();
            } else {
                $errors[] = 'Failed to update the password.';
            }
        } else {
            $errors[] = 'Current password is incorrect.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password</title>
    <style>
        /* CSS styles */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .main {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
        }
        .wrapper {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            width: 100%;
        }
        h1 {
            font-size: 24px;
            margin-bottom: 20px;
            color: #333;
            text-align: center;
        }
        .error-messages, .success-message {
            margin-bottom: 20px;
        }
        .error-messages p, .success-message p {
            background: #f8d7da;
            color: #721c24;
            padding: 10px;
            border-radius: 5px;
            margin: 0;
            text-align: center;
        }
        .success-message p {
            background: #d4edda;
            color: #155724;
        }
        form {
            display: flex;
            flex-direction: column;
        }
        label {
            margin-bottom: 5px;
            font-weight: bold;
            color: #333;
        }
        input[type="password"] {
            margin-bottom: 15px;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
        }
        button {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 15px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
        button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="main">
        <div class="wrapper">
            <h1>Change Password</h1>
            
            <?php if (!empty($errors)): ?>
                <div class="error-messages">
                    <?php foreach ($errors as $error): ?>
                        <p><?php echo htmlspecialchars($error); ?></p>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <?php if (isset($_SESSION['password_change_success'])): ?>
                <div class="success-message">
                    <p><?php echo htmlspecialchars($_SESSION['password_change_success']); ?></p>
                </div>
                <?php unset($_SESSION['password_change_success']); ?>
            <?php endif; ?>

            <form action="" method="post">
                <label for="current_password">Current Password:</label>
                <input type="password" id="current_password" name="current_password" required>
                
                <label for="new_password">New Password:</label>
                <input type="password" id="new_password" name="new_password" required minlength="8">
                
                <label for="confirm_password">Confirm Password:</label>
                <input type="password" id="confirm_password" name="confirm_password" required minlength="8">
                
                <button type="submit">Change Password</button>
            </form>
        </div>
    </div>
</body>
</html>
