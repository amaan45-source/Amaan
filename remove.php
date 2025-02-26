<?php
session_start();
include 'db.php';

if (isset($_POST['product_id']) && isset($_SESSION['user_id'])) {
    $product_id = $_POST['product_id'];
    $user_id = $_SESSION['user_id'];

    // Delete only the selected product for the logged-in user
    $query = "DELETE FROM cart WHERE product_id = '$product_id' AND user_id = '$user_id' LIMIT 1";
    mysqli_query($conn, $query);
}
?>
