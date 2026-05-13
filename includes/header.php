<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// Hitung jumlah item di keranjang
$cart_count = 0;
if(isset($_SESSION['cart'])) {
    foreach($_SESSION['cart'] as $qty) {
        $cart_count += $qty;
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Krisna Jaya Catering</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="Desain/style.css">
</head>
<body>

<header>
    <a href="index.php"><h1>Krisna Jaya</h1></a>
    <nav>
        <a href="cart.php" class="nav-btn">
            🛒 Keranjang 
            <span style="background: rgba(255,255,255,0.2); padding: 2px 8px; border-radius: 10px; margin-left: 5px;">
                <?= $cart_count ?>
            </span>
        </a>
    </nav>
</header>