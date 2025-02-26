<?php
session_start();
include 'db.php';

// Ensure the admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

// Check if product ID is provided
if (isset($_GET['id'])) {
    $product_id = $_GET['id'];

    // Fetch product details to delete the image file
    $query = "SELECT image FROM products WHERE id='$product_id'";
    $result = mysqli_query($conn, $query);
    $product = mysqli_fetch_assoc($result);

    if ($product) {
        $image_path = $product['image'];

        // Delete product from the database
        mysqli_query($conn, "DELETE FROM products WHERE id='$product_id'");

        // Delete image file from the server (optional)
        if (file_exists($image_path)) {
            unlink($image_path);
        }

        // Redirect to manage products page with success message
        header("Location: manage_products.php?success=Product deleted successfully");
        exit();
    } else {
        header("Location: manage_products.php?error=Product not found");
        exit();
    }
} else {
    header("Location: manage_products.php?error=Invalid request");
    exit();
}
?>
