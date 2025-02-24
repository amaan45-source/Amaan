<?php
session_start();
include 'db.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('Please log in to view your cart!'); window.location.href='login.php';</script>";
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch cart items for the logged-in user
$query = "SELECT * FROM cart WHERE user_id = '$user_id'";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center mb-4">🛒 Your Shopping Cart</h2>
        <div class="row">
            <div class="col-lg-8">
                <table class="table table-bordered">
                    <thead class="table-primary">
                        <tr>
                            <th>Image</th>
                            <th>Product</th>
                            <th>Price</th>
                            <th>Quantity</th>
                            <th>Total</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $grand_total = 0;
                        while ($row = mysqli_fetch_assoc($result)) {
                            $total_price = $row['price'] * $row['quantity'];
                            $grand_total += $total_price;
                        ?>
                        <tr>
                            <td><img src="<?= $row['image'] ?>" width="50" height="50"></td>
                            <td><?= $row['name'] ?></td>
                            <td>$<?= number_format($row['price'], 2) ?></td>
                            <td>
                                <button class="btn btn-sm btn-outline-primary update-qty" data-id="<?= $row['product_id'] ?>" data-action="decrease">➖</button>
                                <span id="qty-<?= $row['product_id'] ?>"><?= $row['quantity'] ?></span>
                                <button class="btn btn-sm btn-outline-primary update-qty" data-id="<?= $row['product_id'] ?>" data-action="increase">➕</button>
                            </td>
                            <td>$<?= number_format($total_price, 2) ?></td>
                            <td><button class="btn btn-danger remove-item" data-id="<?= $row['product_id'] ?>">❌</button></td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
            <div class="col-lg-4">
                <div class="card p-3">
                    <h4>Total Amount: <span id="grand-total">$<?= number_format($grand_total, 2) ?></span></h4>
                    <a href="checkout.php" class="btn btn-success w-100 mt-3">Proceed to Checkout</a>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            $(".update-qty").click(function () {
                let product_id = $(this).data("id");
                let action = $(this).data("action");

                $.ajax({
                    url: "update_cart.php",
                    type: "POST",
                    data: { product_id: product_id, action: action },
                    success: function (response) {
                        location.reload();
                    }
                });
            });

            $(".remove-item").click(function () {
                let product_id = $(this).data("id");

                $.ajax({
                    url: "remove.php",
                    type: "POST",
                    data: { product_id: product_id },
                    success: function (response) {
                        location.reload();
                    }
                });
            });
        });
    </script>

</body>
</html>
