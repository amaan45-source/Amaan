<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// // ✅ `total_amount` session me set hai ya nahi check karein
// if (!isset($_SESSION['total_amount'])) {
//     die("Error: Total amount is missing. Please try again.");
// }
$total_amount = $_SESSION['total_amount'];

// ✅ Order ko database me insert karein
$query = "INSERT INTO orders (user_id, total_amount) VALUES ('$user_id', '$total_amount')";
mysqli_query($conn, $query);

$order_id = mysqli_insert_id($conn);

// ✅ Cart se products `order_items` table me daalein
$cart_items = mysqli_query($conn, "SELECT * FROM cart WHERE user_id='$user_id'");
while ($item = mysqli_fetch_assoc($cart_items)) {
    $product_id = $item['product_id'];
    $quantity = $item['quantity'];
    $price = $item['price'];

    mysqli_query($conn, "INSERT INTO order_items (order_id, product_id, quantity, price) 
                         VALUES ('$order_id', '$product_id', '$quantity', '$price')");
}

// ✅ Order ke baad cart clear karein
mysqli_query($conn, "DELETE FROM cart WHERE user_id='$user_id'");

// ✅ Session se `total_amount` remove karein
unset($_SESSION['total_amount']);


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Order Confirmed</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        .thank-you-box {
            background: white;
            padding: 40px;
            border-radius: 10px;
            text-align: center;
            max-width: 500px;
            margin: 100px auto;
        }
        .checkmark-container {
            width: 120px;
            height: 120px;
            background: #28a745;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            position: relative;
            animation: zoomIn 1.6s ease-in-out;
            box-shadow: 0px 0px 20px rgba(22, 236, 72, 0.93);
        }
        .checkmark {
            font-size: 70px;
            color: white;
            font-weight: bold;
        }
        strong{
            color: #28a745;
        }
        @keyframes zoomIn {
            0% { transform: scale(0); opacity: 0; }
            100% { transform: scale(1); opacity: 1; }
        }
    </style>
    <script>
        setTimeout(function() {
            window.location.href = "foot.php"; // Redirect after 5 seconds
        }, 5000);
    </script>
</head>
<body>
    <div class="thank-you-box">
        <div class="checkmark-container">
            <span class="checkmark">✔️</span>
        </div>
        <h2>Thank You for Your Order!</h2>
        <p class="lead">Your order has been <strong>confirmed</strong>. We will deliver it soon!</p>
    </div>
</body>
</html>

