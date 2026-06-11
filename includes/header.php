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
    <a href="index.php" class="brand-logo">
        <img src="../Foto/Logo-Catering.png" alt="Logo Krisna Jaya" class="logo-img">
        <h1>Krisna Jaya</h1>
    </a>
    
    <nav class="header-nav">
 
        <?php if(isset($_SESSION['user_id'])): 
            
            // PERBAIKAN: Pengecekan file dari dalam folder 'includes'
            $path_foto_header = dirname(__DIR__) . '/' . $_SESSION['user_photo'];
            $header_photo = (!empty($_SESSION['user_photo']) && file_exists($path_foto_header)) 
                ? '../' . $_SESSION['user_photo'] . '?v=' . time()  // Penyesuaian path foto profil
                : "https://ui-avatars.com/api/?name=" . urlencode($_SESSION['user_name']) . "&background=1e3a8a&color=fff";
        ?>
            <a href="riwayat.php" style="color: var(--dark); font-weight: 600; text-decoration: none; font-size: 0.95rem; border-right: 2px solid var(--border); padding-right: 15px;">Riwayat Pesanan</a>

            <a href="profil.php" class="header-profile-link">
                <img src="<?= htmlspecialchars($header_photo) ?>" alt="Profil" class="header-profile-img">
                <span class="header-profile-name"><?= htmlspecialchars($_SESSION['user_name']) ?></span>
            </a>
        <?php else: ?>
            <a href="login.php" class="header-login-link">Login</a>
        <?php endif; ?>

        <a href="cart.php" class="nav-btn">
            🛒 Keranjang
            <span class="cart-badge">
                <?= $cart_count ?>
            </span>
        </a>
    </nav>
</header>