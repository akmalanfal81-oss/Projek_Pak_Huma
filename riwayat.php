<?php
// 1. START SESSION & INCLUDE DATABASE
if (session_status() === PHP_SESSION_NONE) { session_start(); }
require 'Database/config.php';
require 'Logic/fuction.php';

// Cek login
if(!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// 2. PROSES PEMBATALAN DI AWAL (Sebelum render HTML)
$message = "";
if(isset($_POST['cancel_order'])) {
    $trx_id = mysqli_real_escape_string($conn, $_POST['trx_id']);
    
    // Pastikan status adalah Menunggu Konfirmasi Admin agar bisa dibatalkan
    $query_update = "UPDATE transactions SET status = 'Dibatalkan' 
                     WHERE id = '$trx_id' AND status = 'Menunggu Konfirmasi Admin'";
                     
    if(mysqli_query($conn, $query_update)) {
        header("Location: riwayat.php?msg=success");
        exit();
    } else {
        $message = "Gagal membatalkan pesanan: " . mysqli_error($conn);
    }
}

// 3. RENDER HEADER
require 'includes/header.php';

$user_name = mysqli_real_escape_string($conn, $_SESSION['user_name']);
$user_phone = mysqli_real_escape_string($conn, $_SESSION['user_phone']);

// Ambil data transaksi
$query_trx = "SELECT * FROM transactions WHERE customer_name = '$user_name' AND phone = '$user_phone' ORDER BY created_at DESC";
$result_trx = mysqli_query($conn, $query_trx);
?>

<div class="history-container">
    <div class="history-header-page">
        <h2 class="history-title">Riwayat Pesanan Anda</h2>
    </div>

    <?php if(isset($_GET['msg'])): ?>
        <div class="alert alert-success">Pesanan berhasil dibatalkan.</div>
    <?php endif; ?>
    <?php if($message): ?>
        <div class="alert alert-error"><?= $message ?></div>
    <?php endif; ?>

    <?php if(mysqli_num_rows($result_trx) == 0): ?>
        <div style="text-align: center; padding: 50px; background: var(--white); border-radius: 12px; box-shadow: var(--shadow);">
            <h3>Belum ada riwayat pesanan.</h3>
        </div>
    <?php else: ?>
        <?php while($trx = mysqli_fetch_assoc($result_trx)): 
            $status_class = "badge-warning";
            if ($trx['status'] == 'Dalam Proses') $status_class = "badge-info";
            elseif ($trx['status'] == 'Dikirim') $status_class = "badge-info";
            elseif ($trx['status'] == 'Selesai') $status_class = "badge-success";
            elseif ($trx['status'] == 'Dibatalkan') $status_class = "badge-danger";
            
            $tanggal = date('d M Y, H:i', strtotime($trx['created_at']));
        ?>
            <div class="history-card">
                <div class="history-card-header">
                    <div>
                        <p class="history-trx-code"><?= htmlspecialchars($trx['trx_code']) ?></p>
                        <p class="history-date"><?= $tanggal ?> WIB</p>
                    </div>
                    <div>
                        <span class="badge badge-type"><?= strtoupper(htmlspecialchars($trx['order_type'])) ?></span>
                        <span class="badge <?= $status_class ?>"><?= htmlspecialchars($trx['status']) ?></span>
                    </div>
                </div>

                <div class="history-card-body">
                    <p class="history-label">Alamat Pengiriman</p>
                    <p class="history-value"><?= nl2br(htmlspecialchars($trx['address'])) ?></p>
                </div>

                <div class="history-card-footer">
                    <div>
                        <p class="history-total-label">Total Pembayaran</p>
                        <p class="history-total-price">
                            <?= ($trx['order_type'] === 'paket') ? '<span style="font-size:1rem; color:var(--secondary);">Menunggu Penawaran</span>' : formatRupiah($trx['total_price']) ?>
                        </p>
                    </div>
                    
                    <div style="display: flex; gap: 10px;">
                        <?php if($trx['status'] == 'Menunggu Konfirmasi Admin'): ?>
                            <form method="POST" onsubmit="return confirm('Yakin ingin membatalkan pesanan ini?');">
                                <input type="hidden" name="trx_id" value="<?= $trx['id'] ?>">
                                <button type="submit" name="cancel_order" class="btn-danger" style="padding: 10px 20px; font-size: 0.9rem;">Batalkan</button>
                            </form>
                        <?php endif; ?>

                        <a href="detail_transaksi.php?id=<?= $trx['id'] ?>" class="btn-action" style="padding: 10px 20px; font-size: 0.9rem;">Lihat Detail</a>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    <?php endif; ?>
</div>

<?php require 'includes/footer.php'; ?>