<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
require 'Database/config.php';
require 'Logic/fuction.php';
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

// Generate Pesan WhatsApp
$link_wa = "https://wa.me/6283836985022?text=" . urlencode("Halo Admin Krisna Jaya, saya ingin konfirmasi pesanan: " . $trx['trx_code'] . ". Metode: " . $trx['payment_method']);
?>

<div class="container">
    <div class="history-card" style="max-width: 800px; margin: auto;">
        <div class="history-card-header" style="background: var(--primary); color: white;">
            <div>
                <p style="margin:0; font-size: 0.85rem; opacity: 0.8;">KODE TRANSAKSI</p>
                <h2 style="margin: 0; font-family: monospace; color: white;"><?= htmlspecialchars($trx['trx_code']) ?></h2>
            </div>
        </div>

        <div class="history-card-body">
            <div style="background: #f0fdf4; padding: 20px; border-radius: 8px; border: 1px solid var(--primary); margin-bottom: 20px;">
                <h3 style="margin-top:0;">Metode Pembayaran: <?= htmlspecialchars($trx['payment_method']) ?></h3>
                
                <?php if($trx['payment_method'] == 'QRIS'): ?>
                    <div style="text-align: center;">
                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=Pembayaran_KrisnaJaya_<?= $trx['trx_code'] ?>" alt="QRIS" style="border: 2px solid #ddd;">
                        <p>Scan QRIS di atas untuk melakukan pembayaran.</p>
                    </div>
                <?php else: ?>
                    <p>Silakan transfer ke rekening berikut:</p>
                    <p style="font-size: 1.2rem; font-weight: bold; color: var(--primary);">
                        BANK <?= strtoupper($trx['payment_method']) ?><br>
                        No. Rek: 1234-5678-9012<br>
                        A/N: Krisna Jaya Catering
                    </p>
                <?php endif; ?>
            </div>

            <h3>Informasi Pengiriman</h3>
            <p>Penerima: <?= htmlspecialchars($trx['customer_name']) ?><br>
            Alamat: <?= htmlspecialchars($trx['address']) ?></p>
        </div>

        <div class="history-card-footer text-center">
            <a href="<?= $link_wa ?>" target="_blank" class="btn-action" style="background: #25d366;">💬 Konfirmasi via WhatsApp</a>
        </div>
    </div>
</div>

<?php require 'includes/footer.php'; ?>