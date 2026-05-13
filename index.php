<?php
require 'Database/config.php';
require 'Logic/fuction.php';

// Logika Tambah ke Keranjang
if (session_status() === PHP_SESSION_NONE) { session_start(); }
if(isset($_GET['add_to_cart'])) {
    $id_menu = $_GET['add_to_cart'];
    if(isset($_SESSION['cart'][$id_menu])) {
        $_SESSION['cart'][$id_menu] += 1;
    } else {
        $_SESSION['cart'][$id_menu] = 1;
    }
    header("Location: index.php#katalog"); 
    exit();
}

$menus = getAllMenus($conn);
require 'includes/header.php';
?>

<div class="hero">
    <h2>Cita Rasa Nusantara, Higienis & Tepat Waktu</h2>
    <p>Solusi profesional untuk kebutuhan konsumsi operasional pabrik, acara perkantoran, maupun momen spesial Anda. Langsung dari dapur kami ke lokasi Anda.</p>
</div>

<div class="container">
    
    <div style="text-align: center;">
        <p class="section-subtitle">Layanan Kami</p>
        <h2 class="section-title">Pilihan Paket Catering</h2>
    </div>
    <p class="center-text">
        Krisna Jaya Catering berdedikasi memberikan layanan jasa boga berkualitas. Pilih paket yang paling sesuai dengan kebutuhan industri atau acara Anda.
    </p>

    <div class="package-grid">
        <a href="detail_paket.php?paket=industri" class="package-card">
            <h3>Pabrik & Industri</h3>
            <p>Konsumsi harian karyawan dengan menu terukur.</p>
            <ul>
                <li>Menu rotasi per 30 hari</li>
                <li>Karbohidrat, Protein, Sayur</li>
                <li>Buah segar / ekstra snack</li>
                <li>Pengiriman tepat waktu</li>
                <li>Opsi permintaan sample</li>
            </ul>
        </a>

        <a href="detail_paket.php?paket=box" class="package-card">
            <h3>Nasi Box Event</h3>
            <p>Praktis dan elegan untuk rapat atau seminar.</p>
            <ul>
                <li>Kemasan bento box premium</li>
                <li>Varian menu Nusantara</li>
                <li>Termasuk air mineral</li>
                <li>Tersegel dan higienis</li>
                <li>Minimum order 30 porsi</li>
            </ul>
        </a>

        <a href="detail_paket.php?paket=prasmanan" class="package-card">
            <h3>Prasmanan</h3>
            <p>Sajian lengkap untuk acara gathering kantor.</p>
            <ul>
                <li>Menu utama & gubukan</li>
                <li>Fasilitas meja & dekorasi</li>
                <li>Dilayani pramusaji</li>
                <li>Dessert & minuman segar</li>
                <li>Minimum order 100 porsi</li>
            </ul>
        </a>
    </div>

    <hr style="border: none; border-top: 1px solid var(--border); margin: 60px 0;">

    <div style="text-align: center;" id="katalog">
        <p class="section-subtitle">Pesan Sekarang</p>
        <h2 class="section-title">Katalog Menu Satuan</h2>
        <p class="center-text" style="margin-bottom: 40px;">
            Pilih menu untuk dimasukkan ke keranjang belanja. Anda dapat melanjutkan untuk <i>Checkout</i> pesanan atau sekadar meminta pengiriman <i>sample</i>.
        </p>
    </div>
    
    <div class="menu-grid">
        <?php foreach($menus as $menu) : ?>
            <div class="menu-card">
                <div class="menu-img-placeholder">
                    [Area Foto <?= htmlspecialchars($menu['name']) ?>]
                </div>
                <h3><?= htmlspecialchars($menu['name']) ?></h3>
                <p class="menu-desc"><?= htmlspecialchars($menu['description']) ?></p>
                <p class="menu-price"><?= formatRupiah($menu['price']) ?></p>
                <a href="index.php?add_to_cart=<?= $menu['id'] ?>" class="btn-action" style="width: 100%; text-align: center;">
                    Tambah ke Keranjang
                </a>
            </div>
        <?php endforeach; ?>
    </div>

</div>

<footer style="background: var(--dark); color: white; text-align: center; padding: 2rem 1rem; margin-top: 4rem;">
    <p style="margin: 0; font-size: 0.9rem; color: #9ca3af;">&copy; <?= date('Y') ?> Krisna Jaya Catering. Sistem Pemesanan Online.</p>
</footer>

</body>
</html>