<?php
session_start();
include 'db.php';

// Fetch all products from the database
$products = mysqli_query($conn, "SELECT * FROM products");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Shop - Footcap</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body { background-color: #f8f9fa; }
        .product-card { transition: transform 0.3s; }
        .product-card:hover { transform: scale(1.05); }
        .product-list { display: flex; flex-wrap: wrap; gap: 20px; justify-content: center; }
        .product-item { width: 300px; }
        .product-img { width: 100%; height: 250px; object-fit: cover; border-radius: 8px; }
        .btn-cart { background-color: #ff9900; color: white; width: 100%; border: none; }
        .btn-cart:hover { background-color: #e68a00; }
    </style>
</head>
<body>

<div class="container mt-5">
    <h2 class="text-center">🛍 Shop Our Products</h2>
    <ul class="product-list">
        <?php while ($row = mysqli_fetch_assoc($products)): ?>
            <li class="product-item">
                <div class="card product-card p-3">
                    <figure class="card-banner">
                        <img src="<?= $row['image'] ?>" class="product-img" alt="<?= $row['name'] ?>">
                    </figure>
                    <div class="card-content">
                        <h5 class="h5 card-title"><?= $row['name'] ?></h5>
                        <p class="text-muted">$<?= number_format($row['price'], 2) ?></p>
                        <form action="add_to_cart.php" method="POST">
                            <input type="hidden" name="product_id" value="<?= $row['id'] ?>">
                            <input type="hidden" name="name" value="<?= $row['name'] ?>">
                            <input type="hidden" name="price" value="<?= $row['price'] ?>">
                            <input type="hidden" name="image" value="<?= $row['image'] ?>">
                            <button type="submit" class="btn btn-cart">🛒 Add to Cart</button>
                        </form>
                    </div>
                </div>
            </li>
        <?php endwhile; ?>
    </ul>
</div>

</body>
</html>
