<?php include('partials/menu.php');?>

<div class="main">
    <div class="wrapper">
        <h1>Category</h1>
        <br>
        <br>

        <?php
            if (isset($_SESSION['add']))
            {
                echo $_SESSION['add'];
                unset($_SESSION['add']);
            }

            if (isset($_SESSION['remove']))
            {
                echo $_SESSION['remove'];
                unset($_SESSION['remove']);
            }

            if (isset($_SESSION['delete']))
            {
                echo $_SESSION['delete'];
                unset($_SESSION['delete']);
            }

            if (isset($_SESSION['no-category-found']))
            {
                echo $_SESSION['no-category-found'];
                unset($_SESSION['no-category-found']);
            }

            if (isset($_SESSION['update']))
            {
                echo $_SESSION['update'];
                unset($_SESSION['update']);
            }

            if (isset($_SESSION['upload']))
            {
                echo $_SESSION['upload'];
                unset($_SESSION['upload']);
            }

            if (isset($_SESSION['failed-remove']))
            {
                echo $_SESSION['failed-remove'];
                unset($_SESSION['failed-remove']);
            }
        ?>

        <br><br>
        <!-- button to add admin -->
        <a href="<?php echo SITEURL; ?>admin/add-category.php" class="btn-primary">Add Category</a>
        <br>
        <br>
        <table class="tbl-full">
            <tr>
                <th>S.N.</th>
                <th>Title</th>
                <th>Image</th>
                <th>Featured</th>
                <th>Active</th>
                <th>Actions</th>
            </tr>

            <?php
                //query to get all category from db
                $sql = "SELECT * FROM tbl_category";

                //execute query
                $res = mysqli_query($conn, $sql);

                //count rows
                $count = mysqli_num_rows($res);

                //create serial num variable and asign value 1
                $sn=1;


                //check wether we have data in db or not
                if($count>0)
                {
                    //data in db
                    //get data and display
                    while($row=mysqli_fetch_assoc($res))
                    {
                        $id = $row['id'];
                        $title = $row['title'];
                        $image_name = $row['image_name'];
                        $featured = $row['featured'];
                        $active = $row['active'];

                        ?>
                            <tr>
                                <td><?php echo $sn++; ?></td>
                                <td><?php echo $title; ?></td>

                                <td>
                                    <?php 
                                        //check wether image name is available or not
                                        if($image_name!="")
                                        {
                                            //display the image
                                    ?>
                                            <img src="<?php echo SITEURL; ?>images/category/<?php echo $image_name; ?>" width="100px">
                                    <?php
                                        }
                                        else
                                        {
                                            //display msg
                                            echo "Image not added";
                                        }
                                    ?>
                                </td>

                                <td><?php echo $featured; ?></td>
                                <td><?php echo $active; ?></td>
                                <td>
                                    <a href="<?php echo SITEURL; ?>admin/update-category.php?id=<?php echo $id; ?>" class="btn-secondary">Update Category</a>
                                    <a href="<?php echo SITEURL; ?>admin/delete-category.php?id=<?php echo $id; ?>&image_name=<?php echo $image_name; ?>" class="btn-secondary1">Delete Category</a>  
                                </td>
                            </tr>

                        <?php

                    }


                }
                    else
                    {
                        //data not available
                        //display msg inside table
                        ?>
                            <tr>
                                <td colspan="6">No Category Added</td>
                            </tr>
                        <?php
                    }
            ?>
            

        </table>

    </div>
            
</div>
<?php include('partials/footer.php')?>