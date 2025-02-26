<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$cart_query = "SELECT * FROM cart WHERE user_id='$user_id'";
$cart_result = mysqli_query($conn, $cart_query);
$total_amount = 0;
while ($row = mysqli_fetch_assoc($cart_result)) {
    $total_amount += $row['price'] * $row['quantity'];
}

// ✅ `total_amount` ko session me store karein
$_SESSION['total_amount'] = $total_amount;
// Check for form submission
$error_message = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST["name"]);
    $mobile = trim($_POST["mobile"]);
    $email = trim($_POST["email"]);
    $address = trim($_POST["address"]);

    // Validate input
    if (empty($name) || empty($mobile) || empty($email) || empty($address)) {
        $error_message = "All fields are required!";
    } else {
        // Calculate total amount
        while ($row = mysqli_fetch_assoc($cart_result)) {
            $total_amount += $row['price'] * $row['quantity'];
        }

        // Insert order details into checkout table
        $stmt = $conn->prepare("INSERT INTO checkout (user_id, name, mobile, email, address, total_amount) VALUES ($user_id,$name, $mobile, $email, $address, $total_amount)");
        // $stmt->bind_param("issssd", $user_id, $name, $mobile, $email, $address, $total_amount);

        header("Location: confirm.php");
        exit();
        if ($stmt->execute()) {
            // Empty cart after successful checkout
            mysqli_query($conn, "DELETE FROM cart WHERE user_id='$user_id'");

            // Redirect to thank you page
        } else {
            $error_message = "Error placing order. Please try again!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Checkout</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
    <h2 class="text-center mb-4">Checkout</h2>

    <div class="row">
        <!-- Billing Details -->
        <div class="col-md-6">
            <h4>Billing Details</h4>
            <form action="checkout.php" method="POST">
                <div class="mb-3">
                    <label class="form-label">Full Name</label>
                    <input type="text" class="form-control" name="name" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Mobile Number</label>
                    <input type="text" class="form-control" name="mobile" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Email Address</label>
                    <input type="email" class="form-control" name="email" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Address</label>
                    <textarea class="form-control" name="address" required></textarea>
                </div>
                <button type="submit" class="btn btn-primary w-100">Place Order</button>
            </form>
        </div>

        <!-- Order Summary -->
        <div class="col-md-6">
            <h4>Order Summary</h4>
            <ul class="list-group mb-3">
                <?php while ($row = mysqli_fetch_assoc($cart_result)) { 
                    $total_amount += $row['price'] * $row['quantity']; ?>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <img src="<?php echo $row['image']; ?>" width="50" height="50" class="rounded">
                        <span><?php echo $row['name']; ?> (x<?php echo $row['quantity']; ?>)</span>
                        <strong>₹<?php echo number_format($row['price'] * $row['quantity'], 2); ?></strong>
                    </li>
                <?php } ?>
                <li class="list-group-item d-flex justify-content-between">
                    <strong>Total Amount:</strong>
                    <strong>₹<?php echo number_format($total_amount, 2); ?></strong>
                </li>
            </ul>

        </div>
    </div>
</div>

</body>
</html>
