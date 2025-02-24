<?php
session_start();
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_POST['product_id'], $_POST['action'])) {
        echo "Error: Missing data!";
        exit();
    }

    $user_id = $_SESSION['user_id'];
    $product_id = $_POST['product_id'];
    $action = $_POST['action'];

    if ($action == "increase") {
        $query = "UPDATE cart SET quantity = quantity + 1 WHERE user_id = '$user_id' AND product_id = '$product_id'";
    } elseif ($action == "decrease") {
        $query = "UPDATE cart SET quantity = GREATEST(quantity - 1, 1) WHERE user_id = '$user_id' AND product_id = '$product_id'";
    }

    if (mysqli_query($conn, $query)) {
        echo "Quantity Updated";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>
