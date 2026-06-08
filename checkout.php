<?php
// Pastikan sesi dimulai di header atau di sini
if (session_status() === PHP_SESSION_NONE) { session_start(); }
require 'includes/header.php';

// Menangkap data dari Cart atau dari Paket
// Jika datang dari cart.php, kita simpan ID yang dipilih ke session
if(isset($_POST['selected_items'])) {
    $_SESSION['checkout_selection'] = $_POST['selected_items'];
} 

$order_type = isset($_GET['type']) ? $_GET['type'] : 'checkout';
$paket_name = isset($_GET['paket']) ? $_GET['paket'] : '';

// Validasi: Pastikan ada sesuatu yang akan di-checkout
if(empty($_SESSION['cart']) && $order_type !== 'paket') {
    header("Location: index.php");
    exit();
}

if(!isset($_SESSION['user_id'])) {
    echo "<script>alert('Silakan Login atau Daftar terlebih dahulu.'); window.location='login.php';</script>";
    exit();
}

$title = ($order_type === 'paket') ? "Form Pemesanan Paket Khusus" : "Form Checkout Pesanan";
?>

<div class="checkout-container">
    <h2 class="checkout-title"><?= $title ?></h2>
    <div class="checkout-card">
        <form action="proses_checkout.php" method="POST">
            <input type="hidden" name="order_type" value="<?= htmlspecialchars($order_type) ?>">
            <input type="hidden" name="paket_name" value="<?= htmlspecialchars($paket_name) ?>">
            
            <div class="form-group">
                <label>Nama Pemesan</label>
                <input type="text" name="customer_name" class="input-readonly" required value="<?= htmlspecialchars($_SESSION['user_name']) ?>" readonly>
            </div>
            
            <div class="form-group">
                <label>Nomor Telepon / WhatsApp</label>
                <input type="tel" name="phone" required value="<?= htmlspecialchars($_SESSION['user_phone']) ?>">
            </div>
            
            <div class="form-group">
                <label>Alamat Lengkap Pengiriman</label>
                <textarea name="address" rows="3" required><?= htmlspecialchars($_SESSION['user_address']) ?></textarea>
            </div>
            
            <div class="form-group">
                <label>Permintaan Khusus (Opsional)</label>
                <textarea name="special_request" rows="2" placeholder="Cth: Catatan tambahan untuk pesanan"></textarea>
            </div>
            
            <div class="form-group">
                <label>Metode Pembayaran</label>
                <select name="payment_method" class="form-group" style="width: 100%; padding: 12px;">
                    <option value="QRIS">QRIS (Semua E-Wallet)</option>
                    <option value="BCA">Transfer BCA</option>
                    <option value="Mandiri">Transfer Mandiri</option>
                    <option value="BNI">Transfer BNI</option>
                </select>
            </div>
            
            <button type="submit" class="btn-action w-100">Konfirmasi Pesanan</button>
        </form>
    </div>
</div>

<?php require 'includes/footer.php'; ?>