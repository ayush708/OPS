<?php include('partials/menu.php'); ?>

<?php
    // Check if form is submitted
    if(isset($_POST['submit'])) {
        // Initialize error variables
        $err_title = '';
        $err = 0;

        // Validate and sanitize inputs
        if(isset($_POST['title']) && !empty(trim($_POST['title']))) {
            $title = trim($_POST['title']);
            
            // Check if the title is unique
            $sql_check = "SELECT * FROM tbl_category WHERE title = ?";
            $stmt_check = mysqli_prepare($conn, $sql_check);
            mysqli_stmt_bind_param($stmt_check, "s", $title);
            mysqli_stmt_execute($stmt_check);
            $res_check = mysqli_stmt_get_result($stmt_check);
            if(mysqli_num_rows($res_check) > 0) {
                $err_title = 'Category title already exists';
                $err++;
            }
        } else {
            $err_title = 'Enter title';
            $err++;
        }

        // Handle radio inputs (featured and active)
        $featured = isset($_POST['featured']) ? $_POST['featured'] : "No";
        $active = isset($_POST['active']) ? $_POST['active'] : "No";

        // Check if an image is uploaded
        if(isset($_FILES['image']['name'])) {
            $image_name = $_FILES['image']['name'];
            $image_tmp = $_FILES['image']['tmp_name'];

            // Check if image is selected and process upload
            if(!empty($image_name)) {
                // Get file extension
                $img_ext = strtolower(pathinfo($image_name, PATHINFO_EXTENSION));

                // Generate unique name for the image
                $image_name = "Category_" . rand(1000, 9999) . '.' . $img_ext;

                // Upload file
                $upload_dir = "../images/category/";
                $upload_path = $upload_dir . $image_name;

                if(move_uploaded_file($image_tmp, $upload_path)) {
                    // Image uploaded successfully
                } else {
                    // Failed to upload image
                    $_SESSION['upload'] = "Failed to Upload Image";
                    header('location:'.SITEURL.'admin/add-category.php');
                    exit;
                }
            } else {
                // No image selected
                $image_name = "";
                $err++;
            }
        } else {
            // No image selected
            $image_name = "";
            $err++;
        }

        // If no errors, proceed to insert into database
        if($err == 0) {
            // Prepare SQL statement
            $sql = "INSERT INTO tbl_category (title, image_name, featured, active) VALUES (?, ?, ?, ?)";
            
            // Prepare statement
            $stmt = mysqli_prepare($conn, $sql);
            
            // Bind parameters
            mysqli_stmt_bind_param($stmt, "ssss", $title, $image_name, $featured, $active);
            
            // Execute statement
            if(mysqli_stmt_execute($stmt)) {
                // Query executed successfully
                $_SESSION['add'] = "Category Added Successfully";
                header('location:'.SITEURL.'admin/category.php');
                exit;
            } else {
                // Failed to execute query
                $_SESSION['add'] = "Failed to Add Category";
                header('location:'.SITEURL.'admin/add-category.php');
                exit;
            }
        }
    }
?>

<div class="main">
    <div class="wrapper">
        
        <br><br>

        <?php
            if(isset($_SESSION['add'])) {
                echo $_SESSION['add'];
                unset($_SESSION['add']);
            }
            
            if(isset($_SESSION['upload'])) {
                echo $_SESSION['upload'];
                unset($_SESSION['upload']);
            }
        ?>

        <br><br>

        <!-- Add category form starts -->
        <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Category</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        .error {
            color: red;
            font-size: 0.875rem;
        }
    </style>
</head>
<body class="bg-gray-100">
    <div class="max-w-3xl mx-auto p-6 bg-white rounded-lg shadow-lg mt-10">
        <h1 class="text-2xl font-semibold mb-6">Add Category</h1>
        
        <form action="" method="POST" enctype="multipart/form-data" class="space-y-6">
            <div>
                <label for="title" class="block text-gray-700 font-medium mb-2">Title:</label>
                <input type="text" id="title" name="title" placeholder="Category Title" class="w-full p-3 border border-gray-300 rounded-lg" value="<?php echo isset($title) ? htmlspecialchars($title) : ''; ?>">
                <span class="error"><?php if(isset($err_title)) echo htmlspecialchars($err_title); ?></span>
            </div>

            <div>
                <label for="image" class="block text-gray-700 font-medium mb-2">Select Image:</label>
                <input type="file" id="image" name="image" class="w-full p-3 border border-gray-300 rounded-lg">
            </div>

            <div>
                <span class="block text-gray-700 font-medium mb-2">Featured:</span>
                <div class="flex items-center space-x-4">
                    <label class="inline-flex items-center">
                        <input type="radio" name="featured" value="Yes" class="form-radio" <?php if(isset($featured) && $featured=="Yes"){echo "checked";} ?>>
                        <span class="ml-2">Yes</span>
                    </label>
                    <label class="inline-flex items-center">
                        <input type="radio" name="featured" value="No" class="form-radio" <?php if(isset($featured) && $featured=="No"){echo "checked";} ?>>
                        <span class="ml-2">No</span>
                    </label>
                </div>
            </div>

            <div>
                <span class="block text-gray-700 font-medium mb-2">Active:</span>
                <div class="flex items-center space-x-4">
                    <label class="inline-flex items-center">
                        <input type="radio" name="active" value="Yes" class="form-radio" <?php if(isset($active) && $active=="Yes"){echo "checked";} ?>>
                        <span class="ml-2">Yes</span>
                    </label>
                    <label class="inline-flex items-center">
                        <input type="radio" name="active" value="No" class="form-radio" <?php if(isset($active) && $active=="No"){echo "checked";} ?>>
                        <span class="ml-2">No</span>
                    </label>
                </div>
            </div>

            <div>
                <input type="submit" name="submit" value="Add Category" class="w-full py-3 px-4 bg-blue-500 text-white rounded-lg cursor-pointer hover:bg-blue-600">
            </div>
        </form>
    </div>
</body>
</html>

        <!-- Add category form ends -->
    </div>
</div>

<?php include('partials/footer.php') ?>
