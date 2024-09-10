<?php 
include('partials/menu.php'); 

// Initialize error array
$errors = [];

// Process form submission with validation
if(isset($_POST['submit'])) {
    // Validate title
    if(empty($_POST['title'])) {
        $errors['title'] = "Title is required";
    } else {
        $title = $_POST['title'];
    }

    // Validate image upload if provided
    $image_name = $_FILES['image']['name'];
    if(!empty($image_name)) {
        $image_tmp = $_FILES['image']['tmp_name'];

        // Check file size and type (example checks, adjust as needed)
        $max_size = 5 * 1024 * 1024; // 5 MB
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];

        // Check file size
        if($_FILES['image']['size'] > $max_size) {
            $errors['image'] = "File size exceeds limit (5MB)";
        }

        // Check file type
        if(!in_array($_FILES['image']['type'], $allowed_types)) {
            $errors['image'] = "Only JPEG, PNG, and GIF files are allowed";
        }
    }

    // If no errors, proceed with update
    if(empty($errors)) {
        // Get ID and all other details
        if(isset($_POST['id'])) {
            $id = $_POST['id'];

            // Check if the new title already exists
            $sql_check_title = "SELECT * FROM tbl_category WHERE title=? AND id!=?";
            $stmt_check = mysqli_prepare($conn, $sql_check_title);

            // Bind parameters
            mysqli_stmt_bind_param($stmt_check, "si", $title, $id);

            // Execute query
            mysqli_stmt_execute($stmt_check);
            $res_check = mysqli_stmt_get_result($stmt_check);

            if(mysqli_num_rows($res_check) > 0) {
                $errors['title'] = "Category name already exists";
            }

            if(empty($errors)) {
                // Fetch existing category details
                $sql = "SELECT * FROM tbl_category WHERE id=?";
                $stmt = mysqli_prepare($conn, $sql);

                // Bind ID parameter
                mysqli_stmt_bind_param($stmt, "i", $id);

                // Execute query
                mysqli_stmt_execute($stmt);

                // Get result
                $res = mysqli_stmt_get_result($stmt);

                // Check whether query is executed and get data
                if(mysqli_num_rows($res) == 1) {
                    // Fetch data
                    $row = mysqli_fetch_assoc($res);
                    $current_image = $row['image_name'];
                    $featured = isset($_POST['featured']) ? $_POST['featured'] : "No";
                    $active = isset($_POST['active']) ? $_POST['active'] : "No";

                    // Handle new image upload
                    if(!empty($image_name)) {
                        // Auto rename image
                        $ext = pathinfo($image_name, PATHINFO_EXTENSION);
                        $image_name = "Category_" . rand(000, 999) . '.' . $ext;

                        // Upload image
                        $upload_dir = "../images/category/";
                        $upload_path = $upload_dir . $image_name;

                        if(move_uploaded_file($image_tmp, $upload_path)) {
                            // Image uploaded successfully, remove current image if exists
                            if($current_image != "") {
                                $remove_path = "../images/category/".$current_image;
                                unlink($remove_path);
                            }
                        } else {
                            // Failed to upload image
                            $_SESSION['upload'] = "Failed to Upload Image";
                            header('location:'.SITEURL.'admin/category.php');
                            exit;
                        }
                    } else {
                        // No new image selected, use current image
                        $image_name = $current_image;
                    }

                    // Update category in database
                    $sql_update = "UPDATE tbl_category SET title=?, image_name=?, featured=?, active=? WHERE id=?";
                    $stmt_update = mysqli_prepare($conn, $sql_update);

                    // Bind parameters
                    mysqli_stmt_bind_param($stmt_update, "ssssi", $title, $image_name, $featured, $active, $id);

                    // Execute update query
                    if(mysqli_stmt_execute($stmt_update)) {
                        // Category updated successfully
                        $_SESSION['update'] = "Category Updated Successfully";
                        header('location:'.SITEURL.'admin/category.php');
                        exit;
                    } else {
                        // Failed to update category
                        $_SESSION['update'] = "Failed to Update Category";
                        header('location:'.SITEURL.'admin/category.php');
                        exit;
                    }
                } else {
                    // Category not found
                    $_SESSION['no-category-found'] = "Category not found";
                    header('location:'.SITEURL.'admin/category.php');
                    exit;
                }
            }
        } else {
            // Redirect if ID is not provided
            header('location:'.SITEURL.'admin/category.php');
            exit;
        }
    }
} else {
    // Check whether ID is set or not
    if(isset($_GET['id'])) {
        // Get ID and all other details
        $id = $_GET['id'];

        // Create SQL query to get all other details
        $sql = "SELECT * FROM tbl_category WHERE id=?";
        $stmt = mysqli_prepare($conn, $sql);

        // Bind ID parameter
        mysqli_stmt_bind_param($stmt, "i", $id);

        // Execute query
        mysqli_stmt_execute($stmt);

        // Get result
        $res = mysqli_stmt_get_result($stmt);

        // Check whether query is executed and get data
        if(mysqli_num_rows($res) == 1) {
            // Fetch data
            $row = mysqli_fetch_assoc($res);
            $title = $row['title'];
            $current_image = $row['image_name'];
            $featured = $row['featured'];
            $active = $row['active'];
        } else {
            // Category not found
            $_SESSION['no-category-found'] = "Category not found";
            header('location:'.SITEURL.'admin/category.php');
            exit;
        }
    } else {
        // Redirect if ID is not provided
        header('location:'.SITEURL.'admin/category.php');
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Category</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 100%;
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .container h1 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }

        .tbl-30 {
            width: 100%;
            border-spacing: 10px;
            margin-bottom: 20px;
        }

        .tbl-30 td {
            padding: 10px;
            vertical-align: top;
        }

        .tbl-30 input[type="text"], 
        .tbl-30 input[type="file"], 
        .tbl-30 input[type="radio"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-sizing: border-box;
        }

        .tbl-30 input[type="radio"] {
            width: auto;
            margin-right: 5px;
        }

        .tbl-30 img {
            border-radius: 5px;
            margin-top: 10px;
        }

        .tbl-30 .btn-secondary {
            display: block;
            width: 100%;
            padding: 12px;
            background-color: #2196F3;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            margin-top: 20px;
            text-align: center;
        }

        .tbl-30 .btn-secondary:hover {
            background-color: #1976D2;
        }

        .error p {
            color: red;
            margin: 0;
        }

        @media (max-width: 600px) {
            .tbl-30 td {
                display: block;
                width: 100%;
                box-sizing: border-box;
            }

            .tbl-30 td input[type="radio"] {
                margin-right: 0;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Update Category</h1>

        <!-- Display validation errors -->
        <?php if(!empty($errors)): ?>
            <div class="error">
                <?php foreach($errors as $error): ?>
                    <p><?php echo $error; ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <form action="" method="POST" enctype="multipart/form-data">
            <table class="tbl-30">
                <tr>
                    <td>Title:</td>
                    <td>
                        <input type="text" name="title" value="<?php echo isset($title) ? htmlspecialchars($title) : ''; ?>">
                    </td>
                </tr>

                <tr>
                    <td>Current Image:</td>
                    <td>
                        <?php if ($current_image != ""): ?>
                            <img src="<?php echo SITEURL; ?>images/category/<?php echo htmlspecialchars($current_image); ?>" width="150px">
                        <?php else: ?>
                            <p style="color: #888;">Image not added</p>
                        <?php endif; ?>
                    </td>
                </tr>

                <tr>
                    <td>New Image:</td>
                    <td>
                        <input type="file" name="image"> 
                    </td>
                </tr>

                <tr>
                    <td>Featured:</td>
                    <td>
                        <input type="radio" name="featured" value="Yes" <?php if (isset($featured) && $featured == "Yes") { echo "checked"; } ?>> Yes
                        <input type="radio" name="featured" value="No" <?php if (isset($featured) && $featured == "No") { echo "checked"; } ?>> No
                    </td>
                </tr>

                <tr>
                    <td>Active:</td>
                    <td>
                        <input type="radio" name="active" value="Yes" <?php if (isset($active) && $active == "Yes") { echo "checked"; } ?>> Yes
                        <input type="radio" name="active" value="No" <?php if (isset($active) && $active == "No") { echo "checked"; } ?>> No
                    </td>
                </tr>

                <tr>
                    <td colspan="2">
                        <input type="hidden" name="current_image" value="<?php echo htmlspecialchars($current_image); ?>">
                        <input type="hidden" name="id" value="<?php echo htmlspecialchars($id); ?>">
                        <input type="submit" name="submit" value="Update Category" class="btn-secondary">
                    </td>
                </tr>
            </table>
        </form>
    </div>
</body>
</html>

<?php include('partials/footer.php'); ?>
