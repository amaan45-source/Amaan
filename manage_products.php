<?php
session_start();
include 'db.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_product'])) {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $image = $_POST['image']; 

    $query = "INSERT INTO products (name, price, image) VALUES ('$name', '$price', '$image')";
    mysqli_query($conn, $query);
    header("Location: manage_products.php");
    exit();
}

$products = mysqli_query($conn, "SELECT * FROM products");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Products</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center">Manage Products</h2>
        <form method="post">
            <div class="mb-3">
                <label>Name</label>
                <input type="text" class="form-control" name="name" required>
            </div>
            <div class="mb-3">
                <label>Price</label>
                <input type="number" class="form-control" name="price" required>
            </div>
            <div class="mb-3">
                <label>Image URL</label>
                <input type="text" class="form-control" name="image" required>
            </div>
            <button type="submit" name="add_product" class="btn btn-success">Add Product</button>
        </form>

        <table class="table mt-4">
            <tr>
                <th>Image</th>
                <th>Name</th>
                <th>Price</th>
                <th>Actions</th>
            </tr>
            <?php while ($row = mysqli_fetch_assoc($products)): ?>
            <tr>
                <td><img src="<?= $row['image'] ?>" width="50"></td>
                <td><?= $row['name'] ?></td>
                <td>$<?= $row['price'] ?></td>
                <td>
                <a href="delete_product.php?id=<?= $row['id'] ?>" class="btn btn-danger btn-sm" 
                     onclick="return confirm('Are you sure you want to delete this product?');">
                      Delete
                 </a>
                </td>

            </tr>
            <?php endwhile; ?>
        </table>
    </div>
</body>
</html>
