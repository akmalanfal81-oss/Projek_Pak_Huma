<?php
require 'Database/config.php';
require 'Logic/fuction.php';
require 'includes/header.php'; // Header dipanggil di awal saja

// Menangkap jenis paket dari URL (misal: ?paket=industri)
$jenis_paket = isset($_GET['paket']) ? $_GET['paket'] : '';

// Menentukan konten berdasarkan paket yang dipilih
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
    // Jika paket tidak ditemukan, kembalikan ke index
    header("Location: index.php");
    exit();
}
?>

<div class="container" style="margin-top: 40px; margin-bottom: 60px;">
    <a href="index.php" style="color: var(--primary); text-decoration: none; font-weight: 600; display: inline-flex; align-items: center; gap: 5px;">
        &larr; Kembali ke Beranda
    </a>
    
    <div style="background: var(--white); border-radius: 12px; box-shadow: var(--shadow); border: 1px solid var(--border); overflow: hidden; margin-top: 20px; display: flex; flex-wrap: wrap;">
        
        <div style="flex: 1; min-width: 300px;">
            <img src="<?= $gambar_placeholder ?>" alt="<?= $judul_paket ?>" style="width: 100%; height: 100%; object-fit: cover; min-height: 350px;">
        </div>

        <div style="flex: 1; padding: 40px; min-width: 300px; display: flex; flex-direction: column; justify-content: center;">
            <p style="color: var(--primary); font-weight: 600; font-size: 0.9rem; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 5px;">Layanan Khusus B2B</p>
            <h2 style="color: var(--dark); font-size: 2.2rem; margin-top: 0; margin-bottom: 15px; font-weight: 700;"><?= $judul_paket ?></h2>
            <p style="color: var(--gray); font-size: 1.1rem; line-height: 1.7; margin-bottom: 30px;">
                <?= $deskripsi_paket ?>
            </p>
            
            <div style="background: var(--light); padding: 25px; border-radius: 8px; border: 1px solid var(--border); margin-bottom: 30px;">
                <p style="margin: 0 0 12px 0; font-weight: 600; color: var(--dark); font-size: 1.05rem;">Cara Pemesanan Paket:</p>
                <ol style="margin: 0; padding-left: 20px; color: var(--gray); font-size: 0.95rem; line-height: 1.6;">
                    <li style="margin-bottom: 8px;">Klik tombol "Ajukan Penawaran" di bawah ini.</li>
                    <li style="margin-bottom: 8px;">Isi formulir dengan data PIC (Person in Charge) dan perusahaan Anda.</li>
                    <li style="margin-bottom: 0;">Tim kami akan menghubungi Anda via WhatsApp untuk mendiskusikan *Term of Payment* (TOP) dan penjadwalan *sample*.</li>
                </ol>
            </div>

            <a href="checkout.php?type=sample&paket=<?= urlencode($jenis_paket) ?>" class="btn-action" style="text-align: center; font-size: 1.1rem; padding: 15px;">
                Ajukan Penawaran / Minta Sample
            </a>
        </div>
    </div>
</div>

<footer style="background: var(--dark); color: white; text-align: center; padding: 2rem 1rem; margin-top: 4rem;">
    <p style="margin: 0; font-size: 0.9rem; color: #9ca3af;">&copy; <?= date('Y') ?> Krisna Jaya Catering. Sistem Pemesanan Online.</p>
</footer>

</body>
</html>