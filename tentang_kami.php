<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
require 'Database/config.php';
require 'Logic/fuction.php'; // Pastikan nama file Logic Anda benar (fuction.php / function.php)
require 'includes/header.php';
?>

<div class="about-hero">
    <div class="hero-wrapper">
        <div class="text-center" style="width: 100%;">
            <h2 style="font-size: 3rem; margin-top: 0; margin-bottom: 10px; font-weight: 800; letter-spacing: -1px; text-shadow: 2px 2px 4px rgba(0,0,0,0.3);">Profil Perusahaan</h2>
            <p style="font-size: 1.15rem; margin: 0 auto; opacity: 0.95; max-width: 700px;">Mengenal lebih dekat PT. Krisna Jaya Abadi Catering. Dedikasi kami dalam menyajikan kualitas dan pelayanan terbaik untuk Anda.</p>
        </div>
    </div>
</div>

<div class="container">
    
    <!-- Bagian Visi Misi -->
    <div class="about-card">
        <h3 class="section-title" style="margin-bottom: 20px;">Visi & Misi</h3>
        <img src="Foto/Visi_Misi_Catering.png" alt="Visi dan Misi Krisna Jaya Catering" class="about-poster">
    </div>

    <!-- Bagian Moto & Sistem Pelayanan -->
    <div class="about-card">
        <h3 class="section-title" style="margin-bottom: 20px;">Moto & Sistem Pelayanan</h3>
        <img src="Foto/Moto_Catering.png" alt="Moto Krisna Jaya Catering" class="about-poster">
    </div>

    <!-- Bagian Struktur Organisasi -->
    <div class="about-card" style="margin-bottom: 80px;">
        <h3 class="section-title" style="margin-bottom: 20px;">Struktur Organisasi</h3>
        <p class="section-desc" style="margin-bottom: 20px;">Didukung oleh tim profesional dan berpengalaman di bidang jasa boga untuk memastikan kepuasan Anda.</p>
        <img src="Foto/Gambar_Struktur_Jabatan.png" alt="Struktur Organisasi Krisna Jaya Catering" class="about-poster">
    </div>

</div>

<?php require 'includes/footer.php'; ?>