<?php
require 'includes/header.php';

// Pastikan keranjang tidak kosong sebelum checkout
if(empty($_SESSION['cart'])) {
    header("Location: index.php");
    exit();
}

$order_type = isset($_GET['type']) ? $_GET['type'] : 'checkout';
$title = ($order_type === 'sample') ? "Form Permintaan Sample" : "Form Checkout Pesanan";
?>

<div class="container" style="max-width: 600px;">
    <h2><?= $title ?></h2>
    <div style="background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
        <form action="proses_checkout.php" method="POST">
            <input type="hidden" name="order_type" value="<?= htmlspecialchars($order_type) ?>">
            
            <div class="form-group">
                <label>Nama Pemesan</label>
                <input type="text" name="customer_name" required>
            </div>
            <div class="form-group">
                <label>Nomor Telepon / WhatsApp</label>
                <input type="tel" name="phone" required>
            </div>
            <div class="form-group">
                <label>Alamat Lengkap Pengiriman</label>
                <textarea name="address" rows="3" required></textarea>
            </div>
            <div class="form-group">
                <label>Permintaan Khusus (Opsional)</label>
                <textarea name="special_request" rows="2" placeholder="Cth: Sambal dipisah"></textarea>
            </div>
            
            <button type="submit" class="btn-action" style="margin-top: 15px;">Konfirmasi Pesanan</button>
        </form>
    </div>
</div>

</body>
</html>