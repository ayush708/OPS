<?php include('partials/menu.php'); ?>

<!-- Main content section starts here -->
<div class="main">
    <div class="wrapper">
        <h1 class="dashboard-title">Dashboard</h1>
        <br>
        <?php 
            if(isset($_SESSION['login'])) {
                echo "<div class='alert-success'>" . $_SESSION['login'] . "</div>";
                unset($_SESSION['login']);
            }
        ?>
        <br>
        <div class="dashboard-container">
            <div class="dashboard-card">
                <?php
                    $sql = "SELECT * FROM tbl_category";
                    $res = mysqli_query($conn, $sql);
                    $count = mysqli_num_rows($res);
                ?>
                <h1><?php echo $count; ?></h1>
                <p>Categories</p>
            </div>

            <div class="dashboard-card">
                <?php
                    $sql2 = "SELECT * FROM tbl_items";
                    $res2 = mysqli_query($conn, $sql2);
                    $count2 = mysqli_num_rows($res2);
                ?>
                <h1><?php echo $count2; ?></h1>
                <p>Items</p>
            </div>

            <div class="dashboard-card">
                <?php
                    $sql3 = "SELECT * FROM tbl_order";
                    $res3 = mysqli_query($conn, $sql3);
                    $count3 = mysqli_num_rows($res3);
                ?>
                <h1><?php echo $count3; ?></h1>
                <p>Total Orders</p>
            </div>

            <div class="dashboard-card">
                <?php
                    $sql4 = "SELECT SUM(total) AS Total FROM tbl_order WHERE status='Delivered'";
                    $res4 = mysqli_query($conn, $sql4);
                    $row4 = mysqli_fetch_assoc($res4);
                    $total_revenue = $row4['Total'];
                ?>
                <h1>Rs. <?php echo number_format($total_revenue, 2); ?></h1>
                <p>Revenue Generated</p>
            </div>
        </div>
    </div>
</div>

<!-- Include styles and scripts -->
<style>
    .dashboard-title {
        text-align: center;
        font-size: 2rem;
        margin-bottom: 20px;
        color: #333;
    }
    .dashboard-container {
        display: flex;
        flex-wrap: wrap;
        justify-content: space-around;
        gap: 20px;
    }
    .dashboard-card {
        background: #fff;
        padding: 20px;
        text-align: center;
        width: 22%;
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s ease-in-out;
    }
    .dashboard-card:hover {
        transform: scale(1.05);
    }
    .dashboard-card h1 {
        font-size: 2.5rem;
        margin: 0;
        color: #007bff;
    }
    .dashboard-card p {
        font-size: 1.2rem;
        color: #555;
    }
    @media (max-width: 768px) {
        .dashboard-card {
            width: 45%;
        }
    }
    @media (max-width: 480px) {
        .dashboard-card {
            width: 100%;
        }
    }
</style>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const cards = document.querySelectorAll(".dashboard-card");
        cards.forEach(card => {
            card.addEventListener("mouseover", () => {
                card.style.boxShadow = "0 6px 12px rgba(0, 0, 0, 0.2)";
            });
            card.addEventListener("mouseout", () => {
                card.style.boxShadow = "0 4px 8px rgba(0, 0, 0, 0.1)";
            });
        });
    });
</script>

<?php include('partials/footer.php'); ?>
