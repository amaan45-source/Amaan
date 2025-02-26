<?php
session_start();
include 'db.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

$total_users = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM users"))['total'];
$total_orders = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM orders"))['total'];
$total_products = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM products"))['total'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center">Admin Dashboard</h2>
        <div class="row text-center">
            <div class="col-md-4">
                <div class="alert alert-primary">
                    <h4>Total Users</h4>
                    <p><?= $total_users ?></p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="alert alert-success">
                    <h4>Total Orders</h4>
                    <p><?= $total_orders ?></p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="alert alert-warning">
                    <h4>Total Products</h4>
                    <p><?= $total_products ?></p>
                </div>
            </div>
        </div>
        <a href="manage_products.php" class="btn btn-info">Manage Products</a>
        <a href="manage_orders.php" class="btn btn-info">Manage Orders</a>
        <a href="admin_logout.php" class="btn btn-danger">Logout</a>
    </div>
</body>
</html>
