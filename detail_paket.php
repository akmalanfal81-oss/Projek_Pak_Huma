<?php
require 'Database/config.php';
require 'Logic/fuction.php';
require 'includes/header.php'; 

$jenis_paket = isset($_GET['paket']) ? $_GET['paket'] : '';

$judul_paket = "";
$deskripsi_paket = "";
$gambar_placeholder = "";

if ($jenis_paket == 'industri') {
    $judul_paket = "Paket Pabrik & Industri";
    $deskripsi_paket = "Konsumsi harian karyawan dengan menu terukur. Karbohidrat, Protein, Sayur, dan Buah segar. Hubungi kami untuk mendiskusikan siklus menu bulanan perusahaan Anda.";
    $gambar_placeholder = "https://images.unsplash.com/photo-1581091226825-a6a2a5aee158?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80"; 
} elseif ($jenis_paket == 'box') {
    $judul_paket = "Paket Nasi Box Event";
    $deskripsi_paket = "Praktis dan elegan untuk rapat atau seminar. Kemasan bento box premium dengan varian menu Nusantara. Minimum pemesanan 30 porsi.";
    $gambar_placeholder = "https://images.unsplash.com/photo-1565557623262-b51c2513a641?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80"; 
} elseif ($jenis_paket == 'prasmanan') {
    $judul_paket = "Paket Prasmanan";
    $deskripsi_paket = "Sajian lengkap untuk acara gathering kantor. Termasuk menu utama, gubukan, fasilitas meja, dekorasi, dan dilayani oleh pramusaji berpengalaman. Minimum pemesanan 100 porsi.";
    $gambar_placeholder = "https://images.unsplash.com/photo-1555244162-803834f70033?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80"; 
} else {
    header("Location: index.php");
    exit();
}
?>

<div class="detail-container">
    <div class="detail-card">
        <div class="detail-img-wrapper">
            <img src="<?= $gambar_placeholder ?>" alt="<?= $judul_paket ?>" class="detail-img">
        </div>

        <div class="detail-content">
            <p class="detail-badge">Layanan Khusus B2B</p>
            <h2 class="detail-title"><?= $judul_paket ?></h2>
            <p class="detail-desc"><?= $deskripsi_paket ?></p>

            <div class="detail-steps-box">
                <p class="detail-steps-title">Cara Pemesanan Paket:</p>
                <ol class="detail-steps-list">
                    <li>Klik tombol "Pesan Paket Sekarang" di bawah ini.</li>
                    <li>Isi formulir pengiriman dan lengkapi data Anda.</li>
                    <li>Tim kami akan menghubungi Anda via WhatsApp untuk mendiskusikan harga, *Term of Payment* (TOP), dan detail pesanan.</li>
                </ol>
            </div>

            <a href="checkout.php?type=paket&paket=<?= urlencode($jenis_paket) ?>" class="btn-action text-center w-100">
                Pesan Paket Sekarang
            </a>
        </div>
    </div>
</div>

<footer class="site-footer">
    <p>&copy; <?= date('Y') ?> Krisna Jaya Catering. Sistem Pemesanan Online.</p>
</footer>

</body>
</html>