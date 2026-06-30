<?php
// Pastikan session dimulai
if (session_status() === PHP_SESSION_NONE) { 
    session_start(); 
}

require 'Database/config.php';
require 'Logic/fuction.php';
require 'includes/header.php';

// 1. Tangkap ID Menu dari URL (contoh: detail_menu.php?id=1)
$id_menu = isset($_GET['id']) ? intval($_GET['id']) : 0;

// 2. Ambil data menu dari database
$menu = getMenuById($conn, $id_menu);

// 3. Jika menu tidak ditemukan, tampilkan pesan error
if (!$menu) {
    echo "<div class='container' style='text-align:center; padding: 100px 20px;'><h3 style='color: var(--danger);'>Menu tidak ditemukan!</h3></div>";
    require 'includes/footer.php';
    exit();
}

// 4. Logika pencocokan gambar (Persis seperti index.php agar sinkron)
$nama_menu_kecil = strtolower($menu['name']);
if ($nama_menu_kecil == 'nasi putih') {
    $img_src = "Foto/Nasi_Putih.jpeg";
} elseif ($nama_menu_kecil == 'acar kuning') {
    $img_src = "Foto/Acar_Kuning.jpeg";
} elseif ($nama_menu_kecil == 'ayam kentucky') {
    $img_src = "Foto/Ayam_Kentucky.png";
} elseif ($nama_menu_kecil == 'daging mercon') {
    $img_src = "Foto/Daging_Mercon.png";
} elseif ($nama_menu_kecil == 'gurame saos telur asin') {
    $img_src = "Foto/Gurame_Saos_Telur_Asin.png";
} elseif ($nama_menu_kecil == 'telur ceplok teriyaki') {
    $img_src = "Foto/Telur_Ceplok_Teriyaki.png";
} elseif ($nama_menu_kecil == 'tahu goreng') {
    $img_src = "Foto/Tahu_Goreng.png";
} elseif ($nama_menu_kecil == 'kerupuk') {
    $img_src = "Foto/Kerupuk.png";
} elseif ($nama_menu_kecil == 'sambal') {
    $img_src = "Foto/Sambal.png";
} elseif ($nama_menu_kecil == 'buah') {
    $img_src = "Foto/Buah.png";
} elseif ($nama_menu_kecil == 'soto betawi') {
    $img_src = "Foto/Soto_Betawi.png";
} elseif ($nama_menu_kecil == 'bebek madura') {
    $img_src = "Foto/Bebek_Madura.png";
} elseif ($nama_menu_kecil == 'ikan fillet saos telur asin') {
    $img_src = "Foto/Ikan_Fillet_Saos_Telur_Asin.png";
} elseif ($nama_menu_kecil == 'udang saos padang') {
    $img_src = "Foto/Udang_Saos_Padang.png";
} elseif ($nama_menu_kecil == 'tempe pedas manis') {
    $img_src = "Foto/Tempe_Pedas_Manis.png";
} elseif ($nama_menu_kecil == 'tahu ondel') {
    $img_src = "Foto/Tahu_Ondel.png";
} elseif ($nama_menu_kecil == 'asinan betawi') {
    $img_src = "Foto/Asinan_Betawi.png";
} elseif ($nama_menu_kecil == 'sambal bajak') {
    $img_src = "Foto/Sambal_Bajak.png";
} else {
    // Foto default jika nama tidak cocok dengan daftar di atas
    $img_src = "https://source.unsplash.com/800x600/?food,indonesian,meal&sig=" . $menu['id'];
}
?>

<div class="detail-container">
    
    <div class="detail-card" style="margin-top: 15px;">
        <!-- Sisi Kiri: Gambar -->
        <div class="detail-img-wrapper">
            <img src="<?= htmlspecialchars($img_src) ?>" alt="<?= htmlspecialchars($menu['name']) ?>" class="detail-img">
        </div>

        <!-- Sisi Kanan: Informasi & Tombol Beli -->
        <div class="detail-content">
            <p class="detail-badge">Katalog Menu Satuan</p>
            <h2 class="detail-title"><?= htmlspecialchars($menu['name']) ?></h2>
            
            <h3 style="color: var(--secondary); font-size: 2.2rem; margin-top: 0; margin-bottom: 20px;">
                <?= formatRupiah($menu['price']) ?>
            </h3>
            
            <p class="detail-desc"><?= nl2br(htmlspecialchars($menu['description'])) ?></p>

            <div class="detail-steps-box" style="background: var(--light); padding: 20px; border-radius: 8px; border: 1px solid var(--border); margin-bottom: 30px;">
                <p style="margin: 0; font-size: 0.95rem; color: var(--dark); font-weight: 600;">Jaminan Kualitas Krisna Jaya:</p>
                <ul style="margin: 10px 0 0 0; padding-left: 20px; color: var(--gray); font-size: 0.9rem; line-height: 1.6;">
                    <li>Diproses dengan standar higienis tinggi</li>
                    <li>Bahan baku segar & berkualitas</li>
                    <li>Sertifikasi Laik Higiene Sanitasi Dinas Kesehatan</li>
                </ul>
            </div>

            <!-- Tombol Tambah ke Keranjang (Mengarah ke Logika index.php) -->
            <a href="index.php?add_to_cart=<?= $menu['id'] ?>" class="btn-action text-center w-100" style="padding: 15px; font-size: 1.1rem;">
                🛒 Tambah ke Keranjang
            </a>
        </div>
    </div>
</div>

<?php require 'includes/footer.php'; ?>