<?php
session_start();
include 'db.php';

// Ensure admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

// Fetch total counts
$total_users = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM users"))['total'];
$total_orders = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM orders"))['total'];
$total_products = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM products"))['total'];
$total_revenue = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(total_amount) as revenue FROM orders"))['revenue'];

// Fetch latest transactions (last 5 orders)
$latest_orders = mysqli_query($conn, "SELECT * FROM orders ORDER BY created_at DESC LIMIT 5");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        .card { transition: transform 0.3s; }
        .card:hover { transform: scale(1.05); }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center">👨‍💼 Admin Dashboard</h2>

        <div class="row text-center">
            <div class="col-md-3">
                <div class="card p-3 bg-primary text-white">
                    <h4>Total Users</h4>
                    <p><?= $total_users ?></p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card p-3 bg-success text-white">
                    <h4>Total Orders</h4>
                    <p><?= $total_orders ?></p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card p-3 bg-warning text-dark">
                    <h4>Total Products</h4>
                    <p><?= $total_products ?></p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card p-3 bg-danger text-white">
                    <h4>Total Revenue</h4>
                    <p>$<?= number_format($total_revenue, 2) ?></p>
                </div>
            </div>
        </div>

        <!-- Recent Transactions Section -->
        <div class="mt-5">
            <h3>📑 Recent Transactions</h3>
            <table class="table table-bordered">
                <thead class="table-light">
                    <tr>
                        <th>Order ID</th>
                        <th>User ID</th>
                        <th>Total Amount</th>
                        <th>Status</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($latest_orders)): ?>
                    <tr>
                        <td><?= $row['id'] ?></td>
                        <td><?= $row['user_id'] ?></td>
                        <td>$<?= number_format($row['total_amount'], 2) ?></td>
                        <td>
                            <span class="badge bg-<?= $row['status'] == 'Pending' ? 'warning' : ($row['status'] == 'Shipped' ? 'primary' : 'success') ?>">
                                <?= $row['status'] ?>
                            </span>
                        </td>
                        <td><?= $row['created_at'] ?></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
            <a href="admin_transactions.php" class="btn btn-primary">View All Transactions</a>
        </div>

        <div class="mt-4">
            <a href="manage_products.php" class="btn btn-info">Manage Products</a>
            <a href="manage_orders.php" class="btn btn-info">Manage Orders</a>
            <a href="admin_logout.php" class="btn btn-danger">Logout</a>
        </div>
    </div>
</body>
</html>
