<?php
session_start();
include 'db.php';

// Ensure admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

// Fetch all orders
$orders = mysqli_query($conn, "SELECT * FROM orders");

// Handle status update
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_status'])) {
    $order_id = $_POST['order_id'];
    $new_status = $_POST['status'];

    mysqli_query($conn, "UPDATE orders SET status='$new_status' WHERE id='$order_id'");
    header("Location: manage_orders.php");
    exit();
}

// Handle order deletion
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_order'])) {
    $order_id = $_POST['order_id'];

    // Delete order items first to maintain integrity
    mysqli_query($conn, "DELETE FROM order_items WHERE order_id='$order_id'");
    mysqli_query($conn, "DELETE FROM orders WHERE id='$order_id'");

    header("Location: manage_orders.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Orders</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center">Manage Orders</h2>
        
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>User ID</th>
                    <th>Total Amount</th>
                    <th>Status</th>
                    <th>Update Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($order = mysqli_fetch_assoc($orders)): ?>
                <tr>
                    <td><?= $order['id'] ?></td>
                    <td><?= $order['user_id'] ?></td>
                    <td>$<?= number_format($order['total_amount'], 2) ?></td>
                    <td><?= $order['status'] ?></td>
                    <td>
                        <form method="POST" class="d-flex">
                            <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
                            <select name="status" class="form-select me-2">
                                <option value="Pending" <?= $order['status'] == 'Pending' ? 'selected' : '' ?>>Pending</option>
                                <option value="Shipped" <?= $order['status'] == 'Shipped' ? 'selected' : '' ?>>Shipped</option>
                                <option value="Delivered" <?= $order['status'] == 'Delivered' ? 'selected' : '' ?>>Delivered</option>
                            </select>
                            <button type="submit" name="update_status" class="btn btn-primary">Update</button>
                        </form>
                    </td>
                    <td>
                        <form method="POST">
                            <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
                            <button type="submit" name="delete_order" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this order?')">Delete</button>
                        </form>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        
        <a href="admin_dashboard.php" class="btn btn-primary">Back to Dashboard</a>
    </div>
</body>
</html>
