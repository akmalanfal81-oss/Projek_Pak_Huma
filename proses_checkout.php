<?php
session_start();
require 'Database/config.php';
require 'Logic/fuction.php';

// Memastikan mode error PHP terlihat agar Anda tahu jika ada masalah
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        // 1. Ambil & Bersihkan Input
        $customer_name = mysqli_real_escape_string($conn, $_POST['customer_name']);
        $phone = mysqli_real_escape_string($conn, $_POST['phone']);
        $address = mysqli_real_escape_string($conn, $_POST['address']);
        $special_request = mysqli_real_escape_string($conn, $_POST['special_request']);
        $order_type = mysqli_real_escape_string($conn, $_POST['order_type']);
        $payment_method = isset($_POST['payment_method']) ? mysqli_real_escape_string($conn, $_POST['payment_method']) : '-';
        $paket_name = isset($_POST['paket_name']) ? mysqli_real_escape_string($conn, $_POST['paket_name']) : '';

        // Gabungkan info paket jika ini pesanan paket
        if ($order_type === 'paket' && !empty($paket_name)) {
            $special_request = "[PESANAN PAKET: " . strtoupper($paket_name) . "] " . $special_request;
        }

        // 2. Generate Kode Transaksi
        $trx_code = "TRX-" . strtoupper(substr(uniqid(), -5)) . rand(10, 99);
        
        // 3. Hitung Total Harga dari item yang dipilih (checkbox)
        $total_price = 0;
        $items_to_process = isset($_SESSION['checkout_selection']) ? $_SESSION['checkout_selection'] : [];
        
        if (!empty($items_to_process)) {
            foreach ($items_to_process as $id_menu) {
                if (isset($_SESSION['cart'][$id_menu])) {
                    $menu = getMenuById($conn, $id_menu);
                    $total_price += ($menu['price'] * $_SESSION['cart'][$id_menu]);
                }
            }
        }

        // 4. Insert ke Tabel Transactions
        $sql_trx = "INSERT INTO transactions (trx_code, customer_name, phone, address, special_request, total_price, order_type, payment_method, status) 
                    VALUES ('$trx_code', '$customer_name', '$phone', '$address', '$special_request', '$total_price', '$order_type', '$payment_method', 'Menunggu Konfirmasi Admin')";
        
        if (mysqli_query($conn, $sql_trx)) {
            $transaction_id = mysqli_insert_id($conn);
            
            // 5. Simpan ke Transaction Details
            foreach ($items_to_process as $id_menu) {
                if (isset($_SESSION['cart'][$id_menu])) {
                    $menu = getMenuById($conn, $id_menu);
                    $qty = $_SESSION['cart'][$id_menu];
                    $subtotal = $menu['price'] * $qty;
                    
                    $sql_detail = "INSERT INTO transaction_details (transaction_id, menu_id, qty, subtotal) 
                                   VALUES ('$transaction_id', '$id_menu', '$qty', '$subtotal')";
                    mysqli_query($conn, $sql_detail);
                    
                    // Hapus item dari keranjang setelah diproses
                    unset($_SESSION['cart'][$id_menu]);
                }
            }
            // Hapus sesi seleksi
            unset($_SESSION['checkout_selection']);
            
            // 6. Tampilkan Halaman Sukses
            require 'includes/header.php';
            ?>
            <div class="container" style="text-align: center; padding: 50px;">
                <h2 style="color: var(--primary);">Pesanan Berhasil Dibuat!</h2>
                <p>Terima kasih, <b><?= htmlspecialchars($customer_name) ?></b>.</p>
                
                <div class="checkout-card" style="max-width: 500px; margin: 30px auto; text-align: left;">
                    <p><strong>Metode Pembayaran:</strong> <?= htmlspecialchars($payment_method) ?></p>
                    <p>Silakan segera lakukan pembayaran dan konfirmasi via WhatsApp Admin.</p>
                    <!-- Tautan ini mengarah ke detail_transaksi.php tempat QRIS unik akan digenerate berdasarkan ID -->
                    <a href="detail_transaksi.php?id=<?= $transaction_id ?>" class="btn-action w-100">Lanjut ke Pembayaran & Konfirmasi</a>
                </div>
            </div>
            <?php
            require 'includes/footer.php';
        }
    } catch (Exception $e) {
        echo "Terjadi kesalahan: " . $e->getMessage();
    }
} else {
    header("Location: cart.php");
    exit();
}
?>