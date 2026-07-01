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

$harga_asli = $trx['total_price']; 
$biaya_admin = 2500; 
$total_pembayaran = $harga_asli + $biaya_admin;
$gopay_number = "089638216020";

// --- MEMBUAT BARCODE YANG SELALU BERUBAH ---
// Kita gabungkan nomor pesanan dengan detik saat ini agar pola barcodenya selalu berubah
$random_string = "GOPAY-" . $trx['trx_code'] . "-" . time();
$dynamic_qris_url = "https://api.qrserver.com/v1/create-qr-code/?size=250x250&data=" . urlencode($random_string);

// --- LINK WHATSAPP ---
$no_wa_admin = "6283836985022"; 
$pesan_wa = "Halo Admin Krisna Jaya! Pembayaran saya telah BERHASIL via *GoPay Dinamis*.\n\n";
$pesan_wa .= "- Kode Transaksi : *" . $trx['trx_code'] . "*\n";
$pesan_wa .= "- Nama Pemesan : " . $trx['customer_name'] . "\n";
$pesan_wa .= "- *Total Dibayar : " . formatRupiah($total_pembayaran) . "*\n\n";
$pesan_wa .= "Mohon diproses!";
$link_wa = "https://wa.me/" . $no_wa_admin . "?text=" . urlencode($pesan_wa);
?>

<style>
    /* CSS Animasi dan Modal */
    .payment-modal-overlay {
        display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%;
        background: rgba(15, 23, 42, 0.85); z-index: 9999; justify-content: center; align-items: center; backdrop-filter: blur(5px);
    }
    .payment-modal {
        background: white; padding: 40px; border-radius: 16px; text-align: center; max-width: 400px; width: 90%;
        box-shadow: 0 20px 25px -5px rgba(0,0,0,0.2); animation: popIn 0.3s ease-out forwards;
    }
    @keyframes popIn { 0% { transform: scale(0.8); opacity: 0; } 100% { transform: scale(1); opacity: 1; } }
    .spinner { width: 60px; height: 60px; border: 6px solid #e5e7eb; border-top-color: #0ea5e9; border-radius: 50%; animation: spin 1s linear infinite; margin: 0 auto 20px auto; }
    @keyframes spin { to { transform: rotate(360deg); } }
    .icon-status { font-size: 80px; margin-bottom: 20px; animation: bounce 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275); display: none; }
    .icon-success { color: #22c55e; }
    .icon-failed { color: #ef4444; }
    @keyframes bounce { 0% { transform: scale(0); } 50% { transform: scale(1.2); } 100% { transform: scale(1); } }
    #modal-title { font-size: 1.5rem; font-weight: bold; margin-bottom: 10px; color: var(--dark); }
    #modal-desc { color: var(--gray); font-size: 1rem; margin-bottom: 25px; line-height: 1.5; }
    .btn-retry { background: #f1f5f9; color: #475569; border: none; padding: 12px 24px; border-radius: 8px; font-weight: 600; cursor: pointer; display: none; margin: 0 auto; width: 100%; transition: 0.2s;}
    .btn-retry:hover { background: #e2e8f0; }

    /* Efek hover pada QR Code agar terlihat seperti tombol */
    .qris-clickable { cursor: pointer; transition: transform 0.2s, box-shadow 0.2s; }
    .qris-clickable:hover { transform: translateY(-5px) scale(1.02); box-shadow: 0 10px 25px rgba(0, 165, 207, 0.4) !important; border-color: #00a5cf !important; }
</style>

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
            
            <div style="background: #ffffff; padding: 20px; border-radius: 8px; border: 1px solid var(--border); margin-bottom: 20px;">
                <div style="display: flex; justify-content: space-between; align-items: center; background: #f8fafc; padding: 15px; border-radius: 8px; border-left: 4px solid var(--secondary);">
                    <span style="color: var(--dark); font-weight: bold; font-size: 1.1rem;">Total yang harus dibayar</span>
                    <span style="color: var(--secondary); font-weight: 800; font-size: 1.4rem;"><?= formatRupiah($total_pembayaran) ?></span>
                </div>
            </div>

            <div style="background: #f0fdf4; padding: 30px 20px; border-radius: 8px; border: 1px solid #22c55e; margin-bottom: 20px; text-align: center;">
                <h3 style="margin-top:0; color: #166534;">Simulasi QRIS Dinamis GoPay</h3>
                <p style="color: var(--dark); font-size: 1rem; margin-bottom: 20px;">
                    Merchant: <strong>MOCHAMMAD AKMAL ANFAL (<?= $gopay_number ?>)</strong><br>
                    Silakan <strong>klik gambar QR Code di bawah ini</strong> untuk memicu simulasi pembayaran otomatis.
                </p>
                
                <?php if($trx['payment_method'] == 'QRIS'): ?>
                    <div style="text-align: center;">
                        <div class="qris-clickable" onclick="prosesPembayaran()" style="
                            width: 250px; height: 250px; margin: 0 auto 20px auto; border-radius: 12px; 
                            box-shadow: 0 4px 12px rgba(0,0,0,0.15); border: 3px solid var(--primary); 
                            background-image: url('<?= $dynamic_qris_url ?>');
                            background-position: center; background-size: cover; background-repeat: no-repeat;">
                        </div>

                        <div style="background: #e0f2fe; padding: 15px; border-radius: 8px; border: 1px dashed #0284c7; max-width: 400px; margin: 0 auto; text-align: left;">
                            <p style="color: var(--primary-dark); font-weight: bold; margin-top: 0; margin-bottom: 10px; font-size: 0.95rem;">Info Simulasi:</p>
                            <ul style="margin: 0; padding-left: 20px; color: var(--dark); font-size: 0.9rem; line-height: 1.6;">
                                <li>Pola barcode di atas akan selalu berubah setiap halamannya di-*refresh*.</li>
                                <li><strong>Klik barcode tersebut</strong> untuk melihat animasi *loading*.</li>
                                <li>Sistem akan mengacak hasil (Sukses/Gagal). Jika sukses, Anda akan langsung dialihkan ke WhatsApp. Jika gagal, halaman akan di-*refresh* untuk membuat barcode baru.</li>
                            </ul>
                        </div>
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
        </div>
    </div>
</div>

<div class="payment-modal-overlay" id="paymentOverlay">
    <div class="payment-modal">
        <div class="spinner" id="modalSpinner"></div>
        <div class="icon-status icon-success" id="iconSuccess">✓</div>
        <div class="icon-status icon-failed" id="iconFailed">✗</div>
        
        <h2 id="modal-title">Mengecek Pembayaran...</h2>
        <p id="modal-desc">Sistem sedang mendeteksi pemotongan saldo dari aplikasi GoPay Anda...</p>
        
        <button class="btn-retry" id="btnRetry" onclick="tutupModal()">Tutup & Hasilkan Barcode Baru</button>
    </div>
</div>

<script>
    function prosesPembayaran() {
        document.getElementById('paymentOverlay').style.display = 'flex';
        document.getElementById('modalSpinner').style.display = 'block';
        document.getElementById('iconSuccess').style.display = 'none';
        document.getElementById('iconFailed').style.display = 'none';
        document.getElementById('btnRetry').style.display = 'none';
        
        document.getElementById('modal-title').innerText = "Mengecek Pembayaran...";
        document.getElementById('modal-title').style.color = "var(--dark)";
        document.getElementById('modal-desc').innerText = "Sistem sedang mendeteksi pemotongan saldo dari aplikasi GoPay Anda...";

        fetch('proses_pembayaran.php')
            .then(response => response.json())
            .then(data => {
                document.getElementById('modalSpinner').style.display = 'none';
                
                if (data.status === 'success') {
                    // SUKSES -> LARI KE WHATSAPP
                    document.getElementById('iconSuccess').style.display = 'block';
                    document.getElementById('modal-title').innerText = "Pembayaran Berhasil!";
                    document.getElementById('modal-title').style.color = "#16a34a";
                    document.getElementById('modal-desc').innerText = data.message + " Menghubungkan ke WhatsApp...";
                    setTimeout(() => { window.location.href = "<?= $link_wa ?>"; }, 2500);
                } 
                else {
                    // GAGAL -> MUNCUL TOMBOL RELOAD
                    document.getElementById('iconFailed').style.display = 'block';
                    document.getElementById('modal-title').innerText = "Pembayaran Gagal";
                    document.getElementById('modal-title').style.color = "#dc2626";
                    document.getElementById('modal-desc').innerText = data.message;
                    document.getElementById('btnRetry').style.display = 'block';
                }
            })
            .catch(error => {
                document.getElementById('modalSpinner').style.display = 'none';
                document.getElementById('iconFailed').style.display = 'block';
                document.getElementById('modal-title').innerText = "Koneksi Terputus";
                document.getElementById('modal-desc').innerText = "Gagal menghubungi server.";
                document.getElementById('btnRetry').style.display = 'block';
            });
    }

    function tutupModal() {
        // Jika gagal, halaman akan di-refresh agar URL barcode-nya terganti dengan yang baru (karena ada time() di PHP)
        location.reload(); 
    }
</script>

<?php require 'includes/footer.php'; ?>