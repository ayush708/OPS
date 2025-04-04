<?php include('partials/menu.php'); ?>

<?php
    // Initialize error array
    $errors = [];

    // Check if form is submitted
    if(isset($_POST['submit'])) {
        // Retrieve form data and validate
        $title = isset($_POST['title']) ? $_POST['title'] : '';
        $description = isset($_POST['description']) ? $_POST['description'] : '';
        $price = isset($_POST['price']) ? $_POST['price'] : '';
        $quantity = isset($_POST['quantity']) ? $_POST['quantity'] : '';

        // Validate title
        if(empty($title)) {
            $errors['title'] = 'Title is required';
        }

        // Validate description
        if(empty($description)) {
            $errors['description'] = 'Description is required';
        }

        // Validate price
        if(empty($price)) {
            $errors['price'] = 'Price is required';
        } elseif(!preg_match('/^\d+(\.\d{1,2})?$/', $price)) {
            $errors['price'] = 'Invalid price format';
        }

        // Validate quantity
        if(empty($quantity) || !is_numeric($quantity) || $quantity <= 0) {
            $errors['quantity'] = 'Please enter a valid quantity';
        }

        // Validate image upload if provided
        if(isset($_FILES['image']['name'])) {
            $image_name = $_FILES['image']['name'];
            if(empty($image_name)) {
                $errors['image'] = 'Please choose an image';
            }
        } else {
            $errors['image'] = 'Image upload error';
        }

        // Proceed if no errors
        if(empty($errors)) {
            // Sanitize and handle image upload
            $image_name = '';
            if(isset($_FILES['image']['name'])) {
                $image_name = $_FILES['image']['name'];
                $image_tmp = $_FILES['image']['tmp_name'];
                $ext = pathinfo($image_name, PATHINFO_EXTENSION);
                $image_name = 'item-name-' . rand(0000, 9999) . '.' . $ext;
                $upload_dir = "../images/item/";
                $upload_path = $upload_dir . $image_name;

                // Upload image
                if(move_uploaded_file($image_tmp, $upload_path)) {
                    // Image uploaded successfully
                } else {
                    // Failed to upload image
                    $_SESSION['upload'] = '<div class="error">Failed to upload image</div>';
                    header('location:'.SITEURL.'admin/add-item.php');
                    exit;
                }
            }

            // Retrieve other form data
            $category = isset($_POST['category']) ? $_POST['category'] : '';
            $featured = isset($_POST['featured']) ? $_POST['featured'] : 'No';
            $active = isset($_POST['active']) ? $_POST['active'] : 'No';

            // Insert into database
            $sql = "INSERT INTO tbl_items (title, description, price, image_name, category_id, featured, active, quantity) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = mysqli_prepare($conn, $sql);

            // Bind parameters
            mysqli_stmt_bind_param($stmt, "ssssissi", $title, $description, $price, $image_name, $category, $featured, $active, $quantity);

            // Execute query
            if(mysqli_stmt_execute($stmt)) {
                // Data inserted successfully
                $_SESSION['add'] = '<div class="success">Item Added Successfully</div>';
                header('location:'.SITEURL.'admin/item.php');
                exit;
            } else {
                // Failed to insert data
                $_SESSION['add'] = '<div class="error">Failed to add Item</div>';
                header('location:'.SITEURL.'admin/item.php');
                exit;
            }
        }
    }
?>

<div class="main-content">
    <div class="wrapper">
        <h1>Add Item</h1>

        <br><br>

        <?php
            // Display upload error message if any
            if(isset($_SESSION['upload'])) {
                echo $_SESSION['upload'];
                unset($_SESSION['upload']);
            }
        ?>

        <!-- Display validation errors -->
        <?php if(!empty($errors)): ?>
            <div class="error">
                <?php foreach($errors as $error): ?>
                    <p><?php echo $error; ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Item</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 900px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        td {
            padding: 10px;
            vertical-align: top;
        }

        td:first-child {
            text-align: right;
            font-weight: bold;
        }

        input[type="text"], 
        input[type="number"], 
        textarea, 
        select, 
        input[type="file"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-sizing: border-box;
            margin-top: 5px;
        }

        textarea {
            resize: vertical;
        }

        .btn-secondary {
            display: block;
            width: 100%;
            padding: 12px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            margin-top: 20px;
            text-align: center;
        }

        .btn-secondary:hover {
            background-color: #0056b3;
        }

        .radio-group {
            display: flex;
            gap: 10px;
            align-items: center;
        }

        .radio-group label {
            margin: 0;
            font-weight: normal;
        }

        @media (max-width: 600px) {
            td:first-child {
                text-align: left;
                font-weight: normal;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Add New Item</h1>

        <form action="" method="POST" enctype="multipart/form-data">
            <table>
                <tr>
                    <td>Title:</td>
                    <td><input type="text" name="title" placeholder="Title of the Item" value="<?php echo isset($title) ? htmlspecialchars($title) : ''; ?>"></td>
                </tr>
                <tr>
                    <td>Description:</td>
                    <td><textarea name="description" cols="30" rows="5" placeholder="Description of Item"><?php echo isset($description) ? htmlspecialchars($description) : ''; ?></textarea></td>
                </tr>
                <tr>
                    <td>Price:</td>
                    <td><input type="text" name="price" placeholder="Price of the Item" value="<?php echo isset($price) ? htmlspecialchars($price) : ''; ?>"></td>
                </tr>
                <tr>
                    <td>Number of Items Left:</td>
                    <td><input type="number" name="quantity" placeholder="Enter quantity available" min="1" value="<?php echo isset($quantity) ? htmlspecialchars($quantity) : ''; ?>"></td>
                </tr>
                <tr>
                    <td>Select Image:</td>
                    <td><input type="file" name="image"></td>
                </tr>
                <tr>
                    <td>Category:</td>
                    <td>
                        <select name="category">
                            <?php
                                // Display categories from database
                                $sql = "SELECT * FROM tbl_category WHERE active='Yes'";
                                $res = mysqli_query($conn, $sql);

                                if(mysqli_num_rows($res) > 0) {
                                    while($row = mysqli_fetch_assoc($res)) {
                                        $id = $row['id'];
                                        $title = $row['title'];
                                        echo "<option value='$id'>$title</option>";
                                    }
                                } else {
                                    echo "<option value='0'>No Category Found</option>";
                                }
                            ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>Featured:</td>
                    <td>
                        <div class="radio-group">
                            <label><input type="radio" name="featured" value="Yes"> Yes</label>
                            <label><input type="radio" name="featured" value="No"> No</label>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>Active:</td>
                    <td>
                        <div class="radio-group">
                            <label><input type="radio" name="active" value="Yes"> Yes</label>
                            <label><input type="radio" name="active" value="No"> No</label>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td colspan="2"><input type="submit" name="submit" value="Add Item" class="btn-secondary"></td>
                </tr>
            </table>
        </form>
    </div>
</body>
</html>
