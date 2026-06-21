<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
require 'Database/config.php';
require 'Logic/fuction.php'; // Pastikan nama file Logic Anda benar
require 'includes/header.php';

if(!isset($_SESSION['user_id'])) { header("Location: login.php"); exit(); }
if(!isset($_GET['id'])) { header("Location: riwayat.php"); exit(); }

$trx_id = mysqli_real_escape_string($conn, $_GET['id']);
$user_name = mysqli_real_escape_string($conn, $_SESSION['user_name']);

$query_trx = "SELECT * FROM transactions WHERE id = '$trx_id' AND customer_name = '$user_name'";
$result_trx = mysqli_query($conn, $query_trx);

if(mysqli_num_rows($result_trx) == 0) {
    echo "<div class='container'><h3>Data Transaksi tidak ditemukan.</h3></div>";
    require 'includes/footer.php'; exit();
}

$trx = mysqli_fetch_assoc($result_trx);
$tanggal = date('d M Y, H:i', strtotime($trx['created_at']));

// ====================================================
// LOGIKA PERHITUNGAN BIAYA (HARGA ASLI + ADMIN)
// ====================================================
$harga_asli = $trx['total_price']; 
$biaya_admin = 2500; // Contoh biaya admin tetap Rp 2.500 (Bisa disesuaikan)
$total_pembayaran = $harga_asli + $biaya_admin;

// ====================================================
// GENERATE PESAN WHATSAPP YANG LEBIH LENGKAP
// ====================================================
$no_wa_admin = "6283836985022"; 

$pesan_wa = "Halo Admin Krisna Jaya, saya ingin konfirmasi pembayaran pesanan saya.\n\n";
$pesan_wa .= "Detail Pesanan:\n";
$pesan_wa .= "- Kode Transaksi : *" . $trx['trx_code'] . "*\n";
$pesan_wa .= "- Nama Pemesan : " . $trx['customer_name'] . "\n";
$pesan_wa .= "- Harga Catering : " . formatRupiah($harga_asli) . "\n";
$pesan_wa .= "- Biaya Admin : " . formatRupiah($biaya_admin) . "\n";
$pesan_wa .= "- *Total Transfer : " . formatRupiah($total_pembayaran) . "*\n";
$pesan_wa .= "- Metode Pembayaran : " . $trx['payment_method'] . "\n\n";

if($trx['payment_method'] == 'QRIS'){
    $pesan_wa .= "Saya telah melakukan scan QRIS dan melampirkan bukti transfer di bawah ini. Mohon segera diproses ya.";
} else {
    $pesan_wa .= "Saya telah melakukan transfer dan melampirkan bukti transfer di bawah ini. Mohon segera diproses ya.";
}

$link_wa = "https://wa.me/" . $no_wa_admin . "?text=" . urlencode($pesan_wa);
?>

<div class="container">
    <div class="history-card" style="max-width: 800px; margin: auto;">
        <div class="history-card-header" style="background: var(--primary); color: white;">
            <div>
                <p style="margin:0; font-size: 0.85rem; opacity: 0.8;">KODE TRANSAKSI</p>
                <h2 style="margin: 0; font-family: monospace; color: white;"><?= htmlspecialchars($trx['trx_code']) ?></h2>
            </div>
            <div style="text-align: right;">
                <p style="margin:0; font-size: 0.85rem; opacity: 0.8;">Tanggal Pesanan</p>
                <p style="margin: 0; font-weight: bold;"><?= $tanggal ?> WIB</p>
            </div>
        </div>

        <div class="history-card-body">
            
            <!-- KOTAK RINCIAN PEMBAYARAN BARU -->
            <div style="background: #ffffff; padding: 20px; border-radius: 8px; border: 1px solid var(--border); margin-bottom: 20px; box-shadow: 0 2px 5px rgba(0,0,0,0.02);">
                <h3 style="margin-top: 0; margin-bottom: 15px; color: var(--dark); border-bottom: 1px solid var(--border); padding-bottom: 10px;">Rincian Pembayaran</h3>
                
                <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                    <span style="color: var(--gray); font-size: 1rem;">Harga Asli Catering</span>
                    <span style="font-weight: 600; font-size: 1rem;"><?= formatRupiah($harga_asli) ?></span>
                </div>
                
                <div style="display: flex; justify-content: space-between; margin-bottom: 15px;">
                    <span style="color: var(--gray); font-size: 1rem;">Biaya Penanganan / Admin</span>
                    <span style="font-weight: 600; font-size: 1rem;"><?= formatRupiah($biaya_admin) ?></span>
                </div>
                
                <div style="display: flex; justify-content: space-between; align-items: center; background: #f8fafc; padding: 15px; border-radius: 8px; border-left: 4px solid var(--secondary);">
                    <span style="color: var(--dark); font-weight: bold; font-size: 1.1rem;">Total yang harus dibayar</span>
                    <span style="color: var(--secondary); font-weight: 800; font-size: 1.4rem;"><?= formatRupiah($total_pembayaran) ?></span>
                </div>
            </div>

            <!-- KOTAK INSTRUKSI PEMBAYARAN -->
            <div style="background: #f0fdf4; padding: 20px; border-radius: 8px; border: 1px solid #22c55e; margin-bottom: 20px;">
                <h3 style="margin-top:0; color: #166534; text-align: center;">Metode: <?= htmlspecialchars($trx['payment_method']) ?></h3>
                
                <?php if($trx['payment_method'] == 'QRIS'): ?>
                    <div style="text-align: center;">
                        <!-- Gambar QRIS Statis atau Dinamis -->
                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=Pembayaran_KrisnaJaya_<?= $trx['trx_code'] ?>_Total_<?= $total_pembayaran ?>" alt="QRIS" style="border: 2px solid #ddd; margin-bottom: 15px; border-radius: 8px; padding: 10px; background: white;">
                        <p style="color: var(--dark); font-size: 0.95rem; margin-bottom: 5px;">1. Buka aplikasi M-Banking / E-Wallet Anda (OVO, Dana, GoPay, dll).</p>
                        <p style="color: var(--dark); font-size: 0.95rem; margin-bottom: 5px;">2. Scan kode QR di atas.</p>
                        <p style="color: var(--dark); font-size: 0.95rem; margin-bottom: 15px;">3. Pastikan nominal yang muncul adalah <strong><?= formatRupiah($total_pembayaran) ?></strong>.</p>
                    </div>
                <?php else: ?>
                    <div style="text-align: center;">
                        <p style="font-size: 1.05rem;">Silakan transfer tepat sebesar <strong style="color: var(--danger);"><?= formatRupiah($total_pembayaran) ?></strong> ke rekening berikut:</p>
                        <div style="background: white; border: 1px dashed var(--gray); padding: 15px; border-radius: 8px; margin: 15px auto; max-width: 300px;">
                            <p style="font-size: 1.2rem; font-weight: bold; color: var(--primary); margin: 0;">BANK <?= strtoupper($trx['payment_method']) ?></p>
                            <p style="font-size: 1.5rem; font-weight: 800; letter-spacing: 2px; margin: 5px 0;">1234-5678-9012</p>
                            <p style="font-size: 0.95rem; color: var(--dark); margin: 0;">A/N: Krisna Jaya Catering</p>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            <!-- KOTAK ALAMAT PENGIRIMAN -->
            <div style="border-top: 1px solid var(--border); padding-top: 15px;">
                <h3 style="margin-bottom: 10px;">Informasi Pengiriman</h3>
                <p style="margin: 0; line-height: 1.6;">
                    <strong>Penerima:</strong> <?= htmlspecialchars($trx['customer_name']) ?><br>
                    <strong>Alamat:</strong> <?= htmlspecialchars($trx['address']) ?>
                </p>
            </div>
        </div>

        <div class="history-card-footer text-center" style="display: flex; flex-direction: column; gap: 10px;">
            <p style="font-size: 0.9rem; color: var(--gray); margin: 0;">Setelah melakukan pembayaran, wajib klik tombol di bawah ini untuk validasi.</p>
            <!-- Tombol WhatsApp -->
            <a href="<?= $link_wa ?>" target="_blank" class="btn-action w-100" style="background: #25d366; display: flex; align-items: center; justify-content: center; gap: 10px; font-size: 1.1rem; padding: 15px;">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"></path></svg>
                Kirim Bukti Pembayaran ke WhatsApp
            </a>
        </div>
    </div>
</div>

<?php require 'includes/footer.php'; ?>