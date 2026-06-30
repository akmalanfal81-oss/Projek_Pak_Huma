<?php
require 'Database/config.php';
require 'Logic/fuction.php';

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

<style>
    /* Mengatur agar teks judul dipaksa 1 baris di PC/Laptop */
    .title-oneline {
        white-space: nowrap;
        font-size: clamp(1.8rem, 3.5vw, 3rem) !important;
    }
    
    /* Jika dibuka di HP, biarkan melipat secara normal agar layar tidak error/melebar ke samping */
    @media (max-width: 768px) {
        .title-oneline {
            white-space: normal;
        }
    }
</style>

<div class="hero">
    <div class="hero-overlay"></div>
 
    <div class="hero-content">
        <h2 class="animate-up title-oneline">Cita Rasa Nusantara, Higienis & Tepat Waktu</h2>
        <p class="animate-up delay-1">Solusi profesional untuk kebutuhan konsumsi operasional pabrik, acara perkantoran, maupun momen spesial Anda. Langsung dari dapur kami ke lokasi Anda.</p>

        <div class="hero-slider-container animate-up delay-3">
            <div class="hero-image-slider">
                <div class="collage-item">
                    <img src="Foto/Foto_Menu_Catering.png" alt="Aneka Nasi Box">
                </div>
                <div class="collage-item">
                    <img src="Foto/Foto_Menu_Catering_2.png" alt="Aneka Prasmanan & Snack">
                </div>
                <div class="collage-item">
                    <img src="Foto/Denah_Jaya_Catering.png" alt="Denah Dapur Kami">
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container" style="margin-top: 3rem; margin-bottom: 2rem;">
    <div class="text-center" id="lokasi">
        <p class="section-subtitle">Kunjungi Dapur Kami</p>
        <h2 class="section-title">Lokasi Krisna Jaya</h2>
        <p class="section-desc" style="margin-bottom: 15px;">
            <strong>PT. Krisna Jaya Abadi</strong> | Jl. Pondok Bambu Batas I No.8, Kel. Pondok Bambu, Kec. Duren Sawit
        </p>
    </div>

    <div class="map-container">
        <iframe
            src="https://maps.google.com/maps?q=Jl.+Pondok+Bambu+Batas+I+No.8,+Pondok+Bambu,+Duren+Sawit&t=&z=16&ie=UTF8&iwloc=&output=embed"
            allowfullscreen=""
            loading="lazy"
            referrerpolicy="no-referrer-when-downgrade">
        </iframe>
    </div>
</div>

<div class="container">
    <div class="text-center">
        <p class="section-subtitle">Layanan Unggulan Kami</p>
        <h2 class="section-title">Pilihan Paket Catering</h2>
        <p class="section-desc">
            Pilih paket yang paling sesuai dengan kebutuhan industri atau acara spesial Anda. Kami menyajikan hidangan terbaik dengan standar kebersihan tertinggi.
        </p>
    </div>

    <div class="package-grid">
        <a href="detail_paket.php?paket=industri" class="package-card">
            <div class="package-content">
                <h3>Pabrik & Industri</h3>
                <p>Konsumsi harian karyawan dengan menu terukur.</p>
                <ul>
                    <li>✅ Menu rotasi per 30 hari</li>
                    <li>✅ Karbohidrat, Protein, Sayur</li>
                    <li>✅ Pengiriman tepat waktu</li>
                </ul>
            </div>
        </a>

        <a href="detail_paket.php?paket=box" class="package-card">
            <div class="package-content">
                <h3>Nasi Box Event</h3>
                <p>Praktis dan elegan untuk rapat atau seminar.</p>
                <ul>
                    <li>✅ Kemasan bento box premium</li>
                    <li>✅ Varian menu Nusantara</li>
                    <li>✅ Minimum order 30 porsi</li>
                </ul>
            </div>
        </a>

        <a href="detail_paket.php?paket=prasmanan" class="package-card">
             <div class="package-content">
                <h3>Prasmanan & Snack</h3>
                <p>Sajian lengkap untuk acara gathering kantor.</p>
                <ul>
                    <li>✅ Menu utama & gubukan</li>
                    <li>✅ Fasilitas meja & dekorasi</li>
                    <li>✅ Minimum order 100 porsi</li>
                </ul>
            </div>
        </a>
    </div>

    <hr class="divider">

    <div class="text-center" id="katalog">
        <p class="section-subtitle">Pesan Sekarang</p>
        <h2 class="section-title">Katalog Menu Satuan</h2>
        <p class="section-desc mb-30">
            Pilih menu satuan untuk dimasukkan ke keranjang belanja Anda.
        </p>
    </div>
 
    <div class="menu-grid">
        <?php foreach($menus as $menu) :
            if (strtolower($menu['name']) == 'nasi putih') {
                $img_src = "Foto/Nasi_Putih.jpeg";
            } elseif (strtolower($menu['name']) == 'acar kuning') {
                $img_src = "Foto/Acar_Kuning.jpeg";
            } elseif (strtolower($menu['name']) == 'ayam kentucky') {
                $img_src = "Foto/Ayam_Kentucky.png";
            } elseif (strtolower($menu['name']) == 'daging mercon') {
                $img_src = "Foto/Daging_Mercon.png";
            } elseif (strtolower($menu['name']) == 'gurame saos telur asin') {
                $img_src = "Foto/Gurame_Saos_Telur_Asin.png";
            } elseif (strtolower($menu['name']) == 'telur ceplok teriyaki') {
                $img_src = "Foto/Telur_Ceplok_Teriyaki.png";
            } elseif (strtolower($menu['name']) == 'tahu goreng') {
                $img_src = "Foto/Tahu_Goreng.png";
            } elseif (strtolower($menu['name']) == 'kerupuk') {
                $img_src = "Foto/Kerupuk.png";
            } elseif (strtolower($menu['name']) == 'sambal') {
                $img_src = "Foto/Sambal.png";
            } elseif (strtolower($menu['name']) == 'buah') {
                $img_src = "Foto/Buah.png";
            } elseif (strtolower($menu['name']) == 'soto betawi') {
                $img_src = "Foto/Soto_Betawi.png";
            } elseif (strtolower($menu['name']) == 'bebek madura') {
                $img_src = "Foto/Bebek_Madura.png";
            } elseif (strtolower($menu['name']) == 'ikan fillet saos telur asin') {
                $img_src = "Foto/Ikan_Fillet_Saos_Telur_Asin.png";
            } elseif (strtolower($menu['name']) == 'udang saos padang') {
                $img_src = "Foto/Udang_Saos_Padang.png";
            } elseif (strtolower($menu['name']) == 'tempe pedas manis') {
                $img_src = "Foto/Tempe_Pedas_Manis.png";
            } elseif (strtolower($menu['name']) == 'tahu ondel') {
                $img_src = "Foto/Tahu_Ondel.png";
            } elseif (strtolower($menu['name']) == 'asinan betawi') {
                $img_src = "Foto/Asinan_Betawi.png";
            } elseif (strtolower($menu['name']) == 'sambal bajak') {
                $img_src = "Foto/Sambal_Bajak.png";
            } else {
                $img_src = "https://source.unsplash.com/400x300/?food,indonesian,meal&sig=" . $menu['id'];
            }
        ?>
            <div class="menu-card">
                
                <a href="detail_menu.php?id=<?= $menu['id'] ?>" style="text-decoration: none; color: inherit; display: flex; flex-direction: column; flex: 1;">
                    <div class="menu-img-placeholder" style="background-image: url('<?= $img_src ?>');">
                    </div>
                    <div class="menu-content" style="padding-bottom: 15px;">
                        <h3><?= htmlspecialchars($menu['name']) ?></h3>
                        <p class="menu-desc"><?= htmlspecialchars($menu['description']) ?></p>
                        <p class="menu-price" style="margin-bottom: 0;"><?= formatRupiah($menu['price']) ?></p>
                    </div>
                </a>
                
                <div style="padding: 0 20px 20px 20px;">
                    <a href="index.php?add_to_cart=<?= $menu['id'] ?>" class="btn-action w-100" style="padding: 12px; font-size: 1rem;">
                        🛒 Tambah Keranjang
                    </a>
                </div>

            </div>
        <?php endforeach; ?>
    </div>
</div>

<?php require 'includes/footer.php'; ?>