<?php include('partials/menu.php'); ?>

<div class="main">
    <div class="wrapper">
        <h1 style="font-size: 32px; color: #2c3e50;">Manage Items</h1>
        <br><br>

        <a href="<?php echo SITEURL; ?>admin/add-item.php" class="btn-add">➕ Add New Item</a>
        <br><br>

        <?php 
            if(isset($_SESSION['add'])) { echo $_SESSION['add']; unset($_SESSION['add']); }
            if(isset($_SESSION['delete'])) { echo $_SESSION['delete']; unset($_SESSION['delete']); }
            if(isset($_SESSION['upload'])) { echo $_SESSION['upload']; unset($_SESSION['upload']); }
            if(isset($_SESSION['unauthorize'])) { echo $_SESSION['unauthorize']; unset($_SESSION['unauthorize']); }
            if(isset($_SESSION['update'])) { echo $_SESSION['update']; unset($_SESSION['update']); }
        ?>

        <table class="modern-table">
            <tr>
                <th>#</th>
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
                                        echo "<span class='text-error'>No Image</span>";
                                    } else {
                                        echo "<img src='" . SITEURL . "images/item/$image_name' class='img-thumb'>";
                                    }
                                ?>
                            </td>
                            <td><?php echo $featured; ?></td>
                            <td><?php echo $active; ?></td>
                            <td>
                                <a href="<?php echo SITEURL; ?>admin/update-item.php?id=<?php echo $id; ?>" class="btn-edit">✏️ Edit</a>
                                <a href="#" class="btn-delete delete-item" data-id="<?php echo $id; ?>" data-image="<?php echo $image_name; ?>">🗑️ Delete</a>
                            </td>
                        </tr>
                        <?php
                    }
                } else {
                    echo "<tr><td colspan='7' class='text-error'>No items added yet.</td></tr>";
                }
            ?>
        </table>
    </div>
</div>

<!-- Confirmation Modal -->
<div id="confirmBox" class="confirm-modal">
    <div class="confirm-content">
        <p>Are you sure you want to delete this item?</p>
        <div class="confirm-buttons">
            <button id="confirmYes" class="btn-confirm">Yes, Delete</button>
            <button id="confirmNo" class="btn-cancel">Cancel</button>
        </div>
    </div>
</div>

<style>
    .btn-add {
        background-color: #27ae60;
        color: white;
        padding: 10px 20px;
        border-radius: 6px;
        text-decoration: none;
        transition: background 0.3s;
    }
    .btn-add:hover {
        background-color: #219150;
    }

    .modern-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }
    .modern-table th, .modern-table td {
        padding: 12px;
        border-bottom: 1px solid #ddd;
        text-align: center;
    }
    .modern-table th {
        background-color: #f4f4f4;
        color: #2c3e50;
    }
    .modern-table tr:hover {
        background-color: #f9f9f9;
    }

    .img-thumb {
        width: 100px;
        height: auto;
        border-radius: 5px;
    }

    .btn-edit, .btn-delete {
        padding: 6px 12px;
        text-decoration: none;
        color: white;
        border-radius: 5px;
        font-size: 14px;
        margin: 2px;
        display: inline-block;
    }
    .btn-edit {
        background-color: #3498db;
    }
    .btn-edit:hover {
        background-color: #2980b9;
    }
    .btn-delete {
        background-color: #e74c3c;
    }
    .btn-delete:hover {
        background-color: #c0392b;
    }

    .text-error {
        color: red;
        font-weight: bold;
    }

    /* Confirmation Modal Styling */
    .confirm-modal {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0,0,0,0.5);
        display: none;
        justify-content: center;
        align-items: center;
        z-index: 1000;
    }

    .confirm-content {
        background: white;
        padding: 30px;
        border-radius: 10px;
        width: 300px;
        text-align: center;
    }

    .confirm-buttons {
        margin-top: 20px;
    }

    .btn-confirm, .btn-cancel {
        padding: 8px 16px;
        border: none;
        border-radius: 6px;
        margin: 0 10px;
        cursor: pointer;
        font-weight: bold;
    }

    .btn-confirm {
        background-color: #e74c3c;
        color: white;
    }

    .btn-cancel {
        background-color: #7f8c8d;
        color: white;
    }

    .btn-confirm:hover {
        background-color: #c0392b;
    }

    .btn-cancel:hover {
        background-color: #6c7a89;
    }
</style>

<script>
    document.querySelectorAll('.delete-item').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const confirmBox = document.getElementById('confirmBox');
            confirmBox.style.display = 'flex';

            document.getElementById('confirmYes').onclick = () => {
                window.location.href = "<?php echo SITEURL; ?>admin/delete-item.php?id=" + this.dataset.id + "&image_name=" + this.dataset.image;
            };

            document.getElementById('confirmNo').onclick = () => {
                confirmBox.style.display = 'none';
            };
        });
    });
</script>

<?php include('partials/footer.php'); ?>
