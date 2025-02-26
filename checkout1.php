<?php
session_start();
$order_confirm=false;
if (!isset($_SESSION['user_id'])) {
    // Redirect to login page if user is not logged in
    header("Location: login.php?redirect=checkout.php");
    exit();
}

// If user is logged in, proceed with order confirmation
include 'db.php'; // Database connection

$user_id = $_SESSION['user_id'];
$total_amount = $_SESSION['total_amount']; // Get total amount from session

// Insert order into the database
$query = "INSERT INTO orders (user_id, total_amount) VALUES ('$user_id', '$total_amount')";
mysqli_query($conn, $query);

$order_id = mysqli_insert_id($conn); // Get last inserted order ID

// Move products from cart to order_items
$cart_items = mysqli_query($conn, "SELECT * FROM cart WHERE user_id='$user_id'");
while ($item = mysqli_fetch_assoc($cart_items)) {
    $product_id = $item['product_id'];
    $quantity = $item['quantity'];
    $price = $item['price'];

    mysqli_query($conn, "INSERT INTO order_items (order_id, product_id, quantity, price) 
                         VALUES ('$order_id', '$product_id', '$quantity', '$price')");
}

// Clear cart after successful order
mysqli_query($conn, "DELETE FROM cart WHERE user_id='$user_id'");

$order_confirm= "Order Confirmed!";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <?php
    if($order_confirm){
        echo '
        <div class="alert alert-success fixed-top text-center" role="alert">
        '.$order_confirm.'
    </div>';
    }
    ?>
</body>
</html>