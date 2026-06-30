<?php
if (session_status() === PHP_SESSION_NONE) { 
    session_start(); 
}

require 'Database/config.php';
require 'Logic/fuction.php';
require 'includes/header.php';
?>

<div class="about-hero">
    <div class="hero-wrapper">
        <div class="text-center" style="width: 100%;">
            <h2 style="font-size: 3rem; margin-top: 0; margin-bottom: 10px; font-weight: 800; letter-spacing: -1px; text-shadow: 2px 2px 4px rgba(0,0,0,0.3);">Legalitas Usaha</h2>
            <p style="font-size: 1.15rem; margin: 0 auto; opacity: 0.95; max-width: 700px;">Bukti keresmian dan legalitas operasi PT. Krisna Jaya Abadi Catering sebagai penyedia jasa boga yang sah di Indonesia.</p>
        </div>
    </div>
</div>

<div class="container" style="margin-bottom: 80px; display: flex; justify-content: center;">
    
    <!-- Kartu latar belakang diperlebar menjadi 1200px agar muat 3 kolom dengan lega -->
    <div class="about-card" style="width: 100%; max-width: 1200px;">
        <h3 class="section-title" style="margin-bottom: 15px;">Dokumen Izin Usaha Resmi</h3>
        <p class="section-desc" style="margin-bottom: 40px;">Kami beroperasi secara resmi dan terdaftar di kementerian terkait untuk menjamin keamanan, kebersihan, dan kenyamanan pelanggan.</p>
        
        <!-- WRAPPER FLEXBOX: Diubah agar menjadi Grid 3 Kolom -->
        <div style="display: flex; flex-wrap: wrap; gap: 30px; width: 100%; justify-content: center;">
            
            <!-- Dokumen 1: NIB -->
            <div style="flex: 1 1 calc(33.333% - 30px); min-width: 250px;">
                <h4 style="color: var(--primary); border-bottom: 2px solid var(--border); padding-bottom: 10px; margin-bottom: 20px; text-align: center; font-size: 1.1rem;">Halaman 1: NIB</h4>
                <img src="Foto/Surat_Izin_Usaha_1.png" alt="Surat Izin Usaha Halaman 1" style="width: 100%; height: auto; border: 1px solid var(--border); border-radius: 8px; box-shadow: 0 4px 8px rgba(0,0,0,0.08);">
            </div>
            
            <!-- Dokumen 2: Lampiran KBLI -->
            <div style="flex: 1 1 calc(33.333% - 30px); min-width: 250px;">
                <h4 style="color: var(--primary); border-bottom: 2px solid var(--border); padding-bottom: 10px; margin-bottom: 20px; text-align: center; font-size: 1.1rem;">Halaman 2: Lampiran KBLI</h4>
                <img src="Foto/Surat_Izin_Usaha_2.png" alt="Surat Izin Usaha Halaman 2" style="width: 100%; height: auto; border: 1px solid var(--border); border-radius: 8px; box-shadow: 0 4px 8px rgba(0,0,0,0.08);">
            </div>

            <!-- Dokumen 3: Izin TDUP -->
            <div style="flex: 1 1 calc(33.333% - 30px); min-width: 250px;">
                <h4 style="color: var(--primary); border-bottom: 2px solid var(--border); padding-bottom: 10px; margin-bottom: 20px; text-align: center; font-size: 1.1rem;">Halaman 3: Izin Usaha (TDUP)</h4>
                <img src="Foto/Surat_Izin_Usaha_3.png" alt="Surat Izin Usaha Pariwisata" style="width: 100%; height: auto; border: 1px solid var(--border); border-radius: 8px; box-shadow: 0 4px 8px rgba(0,0,0,0.08);">
            </div>

            <!-- Dokumen 4: Izin Lokasi -->
            <div style="flex: 1 1 calc(33.333% - 30px); min-width: 250px;">
                <h4 style="color: var(--primary); border-bottom: 2px solid var(--border); padding-bottom: 10px; margin-bottom: 20px; text-align: center; font-size: 1.1rem;">Halaman 4: Izin Lokasi</h4>
                <img src="Foto/Surat_Izin_Usaha_4.png" alt="Surat Izin Lokasi" style="width: 100%; height: auto; border: 1px solid var(--border); border-radius: 8px; box-shadow: 0 4px 8px rgba(0,0,0,0.08);">
            </div>

            <!-- Dokumen 5: Surat Pernyataan Tata Ruang -->
            <div style="flex: 1 1 calc(33.333% - 30px); min-width: 250px;">
                <h4 style="color: var(--primary); border-bottom: 2px solid var(--border); padding-bottom: 10px; margin-bottom: 20px; text-align: center; font-size: 1.1rem;">Halaman 5: Pernyataan Tata Ruang</h4>
                <img src="Foto/Surat_Izin_Usaha_5.png" alt="Surat Pernyataan Tata Ruang" style="width: 100%; height: auto; border: 1px solid var(--border); border-radius: 8px; box-shadow: 0 4px 8px rgba(0,0,0,0.08);">
            </div>

            <!-- Dokumen 6: Sertifikat Higiene Sanitasi -->
            <div style="flex: 1 1 calc(33.333% - 30px); min-width: 250px;">
                <h4 style="color: var(--primary); border-bottom: 2px solid var(--border); padding-bottom: 10px; margin-bottom: 20px; text-align: center; font-size: 1.1rem;">Halaman 6: Sertifikat Laik Higiene</h4>
                <img src="Foto/Surat_Izin_Usaha_6.png" alt="Sertifikat Laik Higiene Sanitasi" style="width: 100%; height: auto; border: 1px solid var(--border); border-radius: 8px; box-shadow: 0 4px 8px rgba(0,0,0,0.08);">
            </div>

            <!-- Dokumen 7: Lampiran Sertifikat Higiene Sanitasi -->
            <div style="flex: 1 1 calc(33.333% - 30px); min-width: 250px;">
                <h4 style="color: var(--primary); border-bottom: 2px solid var(--border); padding-bottom: 10px; margin-bottom: 20px; text-align: center; font-size: 1.1rem;">Halaman 7: Lampiran Laik Higiene</h4>
                <img src="Foto/Surat_Izin_Usaha_7.png" alt="Lampiran Sertifikat Laik Higiene Sanitasi" style="width: 100%; height: auto; border: 1px solid var(--border); border-radius: 8px; box-shadow: 0 4px 8px rgba(0,0,0,0.08);">
            </div>

            <!-- Dokumen 8: Rekomendasi SLHS Dinas Kesehatan -->
            <div style="flex: 1 1 calc(33.333% - 30px); min-width: 250px;">
                <h4 style="color: var(--primary); border-bottom: 2px solid var(--border); padding-bottom: 10px; margin-bottom: 20px; text-align: center; font-size: 1.1rem;">Halaman 8: Rekomendasi SLHS</h4>
                <img src="Foto/Surat_Izin_Usaha_8.png" alt="Rekomendasi SLHS Dinas Kesehatan" style="width: 100%; height: auto; border: 1px solid var(--border); border-radius: 8px; box-shadow: 0 4px 8px rgba(0,0,0,0.08);">
            </div>

            <!-- Dokumen 9: Lampiran Sertifikat Higiene Sanitasi 2 -->
            <div style="flex: 1 1 calc(33.333% - 30px); min-width: 250px;">
                <h4 style="color: var(--primary); border-bottom: 2px solid var(--border); padding-bottom: 10px; margin-bottom: 20px; text-align: center; font-size: 1.1rem;">Halaman 9: Lampiran Laik Higiene 2</h4>
                <img src="Foto/Surat_Izin_Usaha_9.png" alt="Lampiran Sertifikat Laik Higiene Sanitasi 2" style="width: 100%; height: auto; border: 1px solid var(--border); border-radius: 8px; box-shadow: 0 4px 8px rgba(0,0,0,0.08);">
            </div>

        </div>
    </div>

</div>

<?php require 'includes/footer.php'; ?>