<?php include('partials/menu.php'); ?>

<div class="main">
    <div class="wrapper">
        <h1>Item</h1>

        <br><br>
        <!-- Button to add item -->
        <a href="<?php echo SITEURL; ?>admin/add-item.php" class="btn-primary">Add Item</a>
        <br><br>

        <?php 
            if(isset($_SESSION['add'])) {
                echo $_SESSION['add'];
                unset($_SESSION['add']);
            }

            if(isset($_SESSION['delete'])) 
            {
                echo $_SESSION['delete'];
                unset($_SESSION['delete']);
            }

            if(isset($_SESSION['upload'])) 
            {
                echo $_SESSION['upload'];
                unset($_SESSION['upload']);
            }

            if(isset($_SESSION['unauthorize'])) 
            {
                echo $_SESSION['unauthorize'];
                unset($_SESSION['unauthorize']);
            }

            if(isset($_SESSION['update'])) 
            {
                echo $_SESSION['update'];
                unset($_SESSION['update']);
            }
        ?>

        <table class="tbl-full">
            <tr>
                <th>S.N.</th>
                <th>Title</th>
                <th>Price</th>
                <th>Image</th>
                <th>Featured</th>
                <th>Active</th>
                <th>Actions</th>
            </tr>

            <?php
                $sql = "SELECT * FROM tbl_items";
                $res = mysqli_query($conn, $sql);
                $count = mysqli_num_rows($res);
                $sn = 1;

                if($count > 0) {
                    while($row = mysqli_fetch_assoc($res)) {
                        $id = $row['id'];
                        $title = $row['title'];
                        $price = $row['price'];
                        $image_name = $row['image_name'];
                        $featured = $row['featured'];
                        $active = $row['active'];
                        ?>
                        <tr>
                            <td><?php echo $sn++; ?></td>
                            <td><?php echo $title; ?></td>
                            <td>Rs.<?php echo $price; ?></td>
                            <td>
                                <?php 
                                    if($image_name == "") {
                                        echo "<div class='error'>Image not added</div>";
                                    } else {
                                        ?>
                                        <img src="<?php echo SITEURL; ?>images/item/<?php echo $image_name; ?>" width="100px">
                                        <?php
                                    }
                                ?>
                            </td>
                            <td><?php echo $featured; ?></td>
                            <td><?php echo $active; ?></td>
                            <td>
                                <a href="<?php echo SITEURL; ?>admin/update-item.php?id=<?php echo $id; ?>" class="btn-secondary">Update Item</a>
                                <a href="#" class="btn-secondary1 delete-item" data-id="<?php echo $id; ?>" data-image="<?php echo $image_name; ?>">Delete Item</a>  
                            </td>
                        </tr>
                        <?php
                    }
                } else {
                    echo "<tr><td colspan='7' class='error'>Item not added yet</td></tr>";
                }
            ?>
        </table>
    </div>
</div>

<!-- Confirmation Box -->
<div id="confirmBox" class="confirm-box">
    <p>Are you sure you want to delete this item?</p>
    <button id="confirmYes" class="btn-confirm">Yes</button>
    <button id="confirmNo" class="btn-cancel">No</button>
</div>

<script>
    document.querySelectorAll('.delete-item').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const confirmBox = document.getElementById('confirmBox');
            confirmBox.style.display = 'block';
            confirmBox.style.top = e.clientY + 'px';
            confirmBox.style.left = e.clientX + 'px';

            document.getElementById('confirmYes').onclick = () => {
                window.location.href = "<?php echo SITEURL; ?>admin/delete-item.php?id=" + this.dataset.id + "&image_name=" + this.dataset.image;
            };

            document.getElementById('confirmNo').onclick = () => {
                confirmBox.style.display = 'none';
            };
        });
    });
</script>

<style>
    .confirm-box {
        display: none;
        position: absolute;
        background: #fff;
        border: 1px solid #ccc;
        box-shadow: 0 0 10px rgba(0,0,0,0.1);
        padding: 15px;
        border-radius: 8px;
        z-index: 1000;
    }
    .confirm-box p {
        margin: 0 0 15px;
    }
    .btn-confirm {
        background-color: #d9534f;
        color: #fff;
        border: none;
        padding: 8px 12px;
        border-radius: 5px;
        cursor: pointer;
        margin-right: 10px;
    }
    .btn-cancel {
        background-color: #5bc0de;
        color: #fff;
        border: none;
        padding: 8px 12px;
        border-radius: 5px;
        cursor: pointer;
    }
    .btn-secondary1:hover {
        background-color: #d9534f;
        color: #fff;
    }
</style>

<?php include('partials/footer.php'); ?>
