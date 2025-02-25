<?php
session_start();
include 'db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('Please log in to add products to cart!'); window.location.href='login.php';</script>";
    exit();
}

if (isset($_POST['product_id']) && isset($_SESSION['user_id'])) {
    $product_id = $_POST['product_id'];
    $user_id = $_SESSION['user_id'];
    $name = $_POST['name'];
    $price = $_POST['price'];
    $image = $_POST['image'];
   
    // Check if product already exists in cart
    $check_query = "SELECT * FROM cart WHERE product_id = '$product_id' AND user_id = '$user_id'";
    $check_result = mysqli_query($conn, $check_query);

    if (mysqli_num_rows($check_result) > 0) {
        // If product exists, increase its quantity
        $update_query = "UPDATE cart SET quantity = quantity + 1 WHERE product_id = '$product_id' AND user_id = '$user_id'";
        mysqli_query($conn, $update_query);
        header("location: foot.php");
        exit();
    } else {
        // If product doesn't exist, insert new entry
        $insert_query = "INSERT INTO cart (user_id, product_id, name, price, image, quantity) VALUES ('$user_id', '$product_id', '$name', '$price', '$image', 1)";
        mysqli_query($conn, $insert_query);
    }
    
}
?>
