<?php include('partials/menu.php'); ?>

<div class="main">
    <div class="wrapper">
        <h1>Manage Users</h1>

        <?php
            if(isset($_SESSION['delete'])) {
                echo $_SESSION['delete'];
                unset($_SESSION['delete']);
            }
        ?>

        <br><br>

        <!-- Button to add admin -->
        <!-- <a href="add-user.php" class="btn-primary">Add User</a> -->
        <!-- <br><br><br> -->

        <table class="tbl-full">
            <tr>
                <th>S.N.</th>
                <th>Username</th>
                <th>Phone Number</th>
                <th>Actions</th>
            </tr>

            <?php
                // Query to get all users
                $sql = "SELECT * FROM tbl_users";
                $res = mysqli_query($conn, $sql);

                if($res == TRUE) {
                    $count = mysqli_num_rows($res);
                    $sn = 1;

                    if($count > 0) {
                        while($row = mysqli_fetch_assoc($res)) {
                            $username = $row['username'];
                            $phone = $row['phone']; // Retrieve phone number from the database
                            ?>

                            <tr>
                                <td><?php echo $sn++; ?></td>
                                <td><?php echo $username; ?></td>
                                <td><?php echo $phone; ?></td>
                                <td>
                                    <a href="<?php echo SITEURL; ?>admin/delete-user.php?username=<?php echo $username; ?>" class="btn-secondary1">Delete</a>
                                </td>
                            </tr>

                            <?php
                        }
                    } else {
                        ?>

                        <tr>
                            <td colspan="4">No Users Added Yet</td>
                        </tr>

                        <?php
                    }
                }
            ?>

        </table>
    </div>
</div>

<?php include('partials/footer.php'); ?>
