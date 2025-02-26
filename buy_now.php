<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    // Redirect to login page if not logged in
    header("Location: login.php?redirect=buy_now.php");
    exit();
}

// Process immediate purchase logic...
?>
