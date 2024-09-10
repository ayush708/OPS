<?php include('partials/menu.php'); ?>

<div class="main" style="padding: 20px; background-color: #f8f9fa;">
    <div class="wrapper" style="max-width: 1200px; margin: 0 auto;">
        <h1 style="font-size: 2.5em; color: #343a40; text-align: center; margin-bottom: 30px;">Order</h1>

        <br>

        <?php
        if (isset($_SESSION['update'])) {
            echo "<div style='font-size: 1.2em; color: #28a745; text-align: center; margin-bottom: 20px;'>" . $_SESSION['update'] . "</div>";
            unset($_SESSION['update']);
        }
        ?>

        <br>

        <table class="tbl-full" style="width: 100%; border-collapse: collapse; margin-top: 20px; background-color: #ffffff; border-radius: 10px; overflow: hidden; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);">
            <thead style="background-color: #007bff; color: #ffffff;">
                <tr>
                    <th style="padding: 15px; text-align: left;">S.N.</th>
                    <th style="padding: 15px; text-align: left;">Item</th>
                    <th style="padding: 15px; text-align: left;">Price</th>
                    <th style="padding: 15px; text-align: left;">Qty.</th>
                    <th style="padding: 15px; text-align: left;">Total</th>
                    <th style="padding: 15px; text-align: left;">Order Date</th>
                    <th style="padding: 15px; text-align: left;">Status</th>
                    <th style="padding: 15px; text-align: left;">Customer Name</th>
                    <th style="padding: 15px; text-align: left;">Contact</th>
                    <th style="padding: 15px; text-align: left;">Email</th>
                    <th style="padding: 15px; text-align: left;">Address</th>
                    <th style="padding: 15px; text-align: left;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Get all the orders from the database
                $sql = "SELECT * FROM tbl_order ORDER BY id DESC"; // Display the latest order at the top
                $res = mysqli_query($conn, $sql);
                $count = mysqli_num_rows($res);

                $sn = 1;

                if ($count > 0) {
                    // Orders available
                    while ($row = mysqli_fetch_assoc($res)) {
                        // Get all the order details
                        $id = $row['id'];
                        $item = $row['item'];
                        $price = $row['price'];
                        $qty = $row['qty'];
                        $total = $row['total'];
                        $order_date = $row['order_date'];
                        $status = $row['status'];
                        $customer_name = $row['customer_name'];
                        $customer_contact = $row['customer_contact'];
                        $customer_email = $row['customer_email'];
                        $customer_address = $row['customer_address'];
                        ?>
                        <tr style="background-color: #f8f9fa;">
                            <td style="padding: 15px;"><?php echo $sn++; ?></td>
                            <td style="padding: 15px;"><?php echo $item; ?></td>
                            <td style="padding: 15px;">Rs.<?php echo $price; ?></td>
                            <td style="padding: 15px;"><?php echo $qty; ?></td>
                            <td style="padding: 15px;">Rs.<?php echo $total; ?></td>
                            <td style="padding: 15px;"><?php echo $order_date; ?></td>
                            <td style="padding: 15px;"><?php echo $status; ?></td>
                            <td style="padding: 15px;"><?php echo $customer_name; ?></td>
                            <td style="padding: 15px;"><?php echo $customer_contact; ?></td>
                            <td style="padding: 15px;"><?php echo $customer_email; ?></td>
                            <td style="padding: 15px;"><?php echo $customer_address; ?></td>
                            <td style="padding: 15px; text-align: center;">
                                <a href="<?php echo SITEURL; ?>admin/update-order.php?id=<?php echo $id; ?>" style="display: inline-block; background-color: #007bff; color: #ffffff; padding: 10px 15px; border-radius: 5px; text-decoration: none;">Update</a>
                            </td>
                        </tr>
                        <?php
                    }
                } else {
                    // No orders available
                    echo "<tr><td colspan='12' style='padding: 20px; text-align: center; color: #6c757d;'>Order Not Available</td></tr>";
                }
                ?>
            </tbody>
        </table>

    </div>
</div>

<!-- Inline CSS for responsiveness -->
<style>
    @media (max-width: 768px) {
        .tbl-full {
            display: block;
            overflow-x: auto;
        }

        .tbl-full thead {
            display: none;
        }

        .tbl-full tr {
            display: block;
            margin-bottom: 15px;
        }

        .tbl-full td {
            display: block;
            text-align: right;
            padding-left: 50%;
            position: relative;
        }

        .tbl-full td::before {
            content: attr(data-label);
            position: absolute;
            left: 0;
            width: 50%;
            padding-left: 15px;
            font-weight: bold;
            text-align: left;
        }

        .tbl-full td:last-child {
            border-bottom: none;
        }

        .tbl-full td a {
            display: block;
            width: 100%;
            text-align: center;
            margin-top: 10px;
        }
    }
</style>

<?php include('partials/footer.php'); ?>
