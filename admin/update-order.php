<?php include('partials/menu.php'); ?>

<div class="main" style="padding: 20px; background-color: #f8f9fa;">
    <div class="wrapper" style="max-width: 1200px; margin: 0 auto;">
        <h1 style="font-size: 2.5em; color: #343a40; text-align: center; margin-bottom: 30px;">Update Order</h1>
        <br><br>

        <?php
        // Check whether id is set or not
        if (isset($_GET['id'])) {
            // Get order details
            $id = $_GET['id'];
            // Get all order details based on id
            $sql = "SELECT * FROM tbl_order WHERE id=$id";
            $res = mysqli_query($conn, $sql);
            $count = mysqli_num_rows($res);
            if ($count == 1) {
                $row = mysqli_fetch_assoc($res);
                $item = $row['item'];
                $price = $row['price'];
                $qty = $row['qty'];
                $status = $row['status'];
                $customer_name = $row['customer_name'];
                $customer_contact = $row['customer_contact'];
                $customer_email = $row['customer_email'];
                $customer_address = $row['customer_address'];
            } else {
                header('location:' . SITEURL . 'admin/order.php');
            }
        } else {
            header('location:' . SITEURL . 'admin/order.php');
        }

        // Handle form submission
        $err = [];
        if (isset($_POST['submit'])) {
            $id = $_POST['id'];
            $status = $_POST['status'];

            $sql2 = "UPDATE tbl_order SET
                status = '$status'
                WHERE id=$id";

            $res2 = mysqli_query($conn, $sql2);

            if ($res2 == true) {
                $_SESSION['update'] = "<div class='success' style='color: #28a745;'>Order Updated Successfully</div>";
                header('location:' . SITEURL . 'admin/order.php');
            } else {
                $_SESSION['update'] = "<div class='error' style='color: #dc3545;'>Failed to Update Order</div>";
                header('location:' . SITEURL . 'admin/order.php');
            }
        }
        ?>

        <form action="" method="POST" style="max-width: 600px; margin: 0 auto; padding: 20px; background-color: #ffffff; border-radius: 8px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);">
            <?php if (!empty($err)): ?>
                <div class="error" style="color: #dc3545; margin-bottom: 20px;">
                    <?php foreach ($err as $error): ?>
                        <p><?php echo $error; ?></p>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <table style="width: 100%; border-collapse: collapse;">
                <tr>
                    <td style="padding: 10px;">Item Name</td>
                    <td style="padding: 10px;"><b><?php echo $item; ?></b></td>
                </tr>
                <tr>
                    <td style="padding: 10px;">Price</td>
                    <td style="padding: 10px;"><b>$<?php echo $price; ?></b></td>
                </tr>
                <tr>
                    <td style="padding: 10px;">Qty</td>
                    <td style="padding: 10px;">
                        <input type="number" name="qty" value="<?php echo $qty; ?>" readonly style="width: 100%; padding: 8px; border: 1px solid #ced4da; border-radius: 4px;">
                    </td>
                </tr>
                <tr>
                    <td style="padding: 10px;">Status</td>
                    <td style="padding: 10px;">
                        <select name="status" required style="width: 100%; padding: 8px; border: 1px solid #ced4da; border-radius: 4px;">
                            <option <?php if ($status == "Ordered") { echo "selected"; } ?> value="Ordered">Ordered</option>
                            <option <?php if ($status == "On Delivery") { echo "selected"; } ?> value="On Delivery">On Delivery</option>
                            <option <?php if ($status == "Delivered") { echo "selected"; } ?> value="Delivered">Delivered</option>
                            <option <?php if ($status == "Cancelled") { echo "selected"; } ?> value="Cancelled">Cancelled</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td style="padding: 10px;">Customer Name</td>
                    <td style="padding: 10px;">
                        <input type="text" name="customer_name" value="<?php echo $customer_name; ?>" readonly style="width: 100%; padding: 8px; border: 1px solid #ced4da; border-radius: 4px;">
                    </td>
                </tr>
                <tr>
                    <td style="padding: 10px;">Customer Contact</td>
                    <td style="padding: 10px;">
                        <input type="text" name="customer_contact" value="<?php echo $customer_contact; ?>" readonly style="width: 100%; padding: 8px; border: 1px solid #ced4da; border-radius: 4px;">
                    </td>
                </tr>
                <tr>
                    <td style="padding: 10px;">Customer Email</td>
                    <td style="padding: 10px;">
                        <input type="email" name="customer_email" value="<?php echo $customer_email; ?>" readonly style="width: 100%; padding: 8px; border: 1px solid #ced4da; border-radius: 4px;">
                    </td>
                </tr>
                <tr>
                    <td style="padding: 10px;">Customer Address</td>
                    <td style="padding: 10px;">
                       <textarea name="customer_address" cols="30" rows="5" readonly style="width: 100%; padding: 8px; border: 1px solid #ced4da; border-radius: 4px;"><?php echo $customer_address; ?></textarea>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" style="padding: 10px; text-align: center;">
                        <input type="hidden" name="id" value="<?php echo $id; ?>">
                        <input type="hidden" name="price" value="<?php echo $price; ?>">
                        <input type="submit" name="submit" value="Update Status" class="btn-secondary" style="background-color: #007bff; color: #ffffff; padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer;">
                    </td>
                </tr>
            </table>
        </form>
        
    </div>
</div>

<?php include('partials/footer.php'); ?>

<!-- Inline CSS for responsiveness -->
<style>
    @media (max-width: 768px) {
        .wrapper {
            padding: 10px;
        }

        table, table td {
            display: block;
            width: 100%;
        }

        table td {
            padding: 10px;
            box-sizing: border-box;
        }

        input, select, textarea {
            width: calc(100% - 20px);
            margin-bottom: 10px;
        }

        .btn-secondary {
            width: 100%;
        }
    }
</style>
