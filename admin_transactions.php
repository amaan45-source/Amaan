<?php
session_start();
include 'db.php';

// Ensure admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

// Fetch transactions/orders
$filter = isset($_GET['status']) ? $_GET['status'] : 'All';
$query = "SELECT * FROM orders";
if ($filter !== 'All') {
    $query .= " WHERE status = '$filter'";
}
$transactions = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Transactions - Admin Panel</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center">🧾 Transactions</h2>

        <!-- Filter Transactions -->
        <div class="mb-3">
            <label>Filter by Status:</label>
            <select onchange="location = this.value;" class="form-select w-25">
                <option value="admin_transactions.php?status=All" <?= $filter == 'All' ? 'selected' : '' ?>>All</option>
                <option value="admin_transactions.php?status=Pending" <?= $filter == 'Pending' ? 'selected' : '' ?>>Pending</option>
                <option value="admin_transactions.php?status=Shipped" <?= $filter == 'Shipped' ? 'selected' : '' ?>>Shipped</option>
                <option value="admin_transactions.php?status=Delivered" <?= $filter == 'Delivered' ? 'selected' : '' ?>>Delivered</option>
            </select>
        </div>

        <table class="table table-bordered">
            <thead class="table-primary">
                <tr>
                    <th>Order ID</th>
                    <th>User ID</th>
                    <th>Total Amount</th>
                    <th>Status</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($transactions)): ?>
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

        <a href="admin_dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
    </div>
</body>
</html>
