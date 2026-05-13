<?php
session_start();
require 'Database/config.php';
require 'Logic/fuction.php';

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $customer_name = mysqli_real_escape_string($conn, $_POST['customer_name']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $special_request = mysqli_real_escape_string($conn, $_POST['special_request']);
    $order_type = mysqli_real_escape_string($conn, $_POST['order_type']);
    
    // Generate Kode Transaksi Unik
    $trx_code = "TRX-" . strtoupper(substr(uniqid(), -5)) . rand(10,99);
    
    // Hitung Total Belanja
    $total_price = 0;
    foreach($_SESSION['cart'] as $id_menu => $qty) {
        $menu = getMenuById($conn, $id_menu);
        $total_price += ($menu['price'] * $qty);
    }

    // 1. Insert ke tabel transactions
    $sql_trx = "INSERT INTO transactions (trx_code, customer_name, phone, address, special_request, total_price, order_type) 
                VALUES ('$trx_code', '$customer_name', '$phone', '$address', '$special_request', '$total_price', '$order_type')";
    
    if(mysqli_query($conn, $sql_trx)) {
        $transaction_id = mysqli_insert_id($conn); // Ambil ID transaksi yang baru dibuat
        
        // 2. Insert ke tabel transaction_details
        foreach($_SESSION['cart'] as $id_menu => $qty) {
            $menu = getMenuById($conn, $id_menu);
            $subtotal = $menu['price'] * $qty;
            
            $sql_detail = "INSERT INTO transaction_details (transaction_id, menu_id, qty, subtotal) 
                           VALUES ('$transaction_id', '$id_menu', '$qty', '$subtotal')";
            mysqli_query($conn, $sql_detail);
        }
        
        // 3. Kosongkan keranjang setelah berhasil
        unset($_SESSION['cart']);
        
        // Tampilkan halaman sukses
        require 'includes/header.php';
        ?>
        <div class="container" style="text-align: center; margin-top: 50px;">
            <h2 style="color: var(--primary);">Pesanan Berhasil Dibuat!</h2>
            <p>Terima kasih, <b><?= htmlspecialchars($customer_name) ?></b>.</p>
            <div style="background: white; padding: 20px; border-radius: 8px; display: inline-block; box-shadow: 0 2px 5px rgba(0,0,0,0.1); margin: 20px 0;">
                <p>Kode Transaksi Anda:</p>
                <h1 style="margin: 0; color: var(--primary); font-family: monospace;"><?= $trx_code ?></h1>
            </div>
            <p>Silakan simpan kode transaksi di atas untuk mengecek status pesanan.</p>
            <p>Sesuai prosedur, silakan hubungi Admin melalui WhatsApp untuk konfirmasi.</p>
            <br>
            <a href="index.php" class="btn-action" style="width: 200px; margin: 0 auto;">Kembali ke Beranda</a>
        </div>
        </body></html>
        <?php
    } else {
        echo "Error: " . $sql_trx . "<br>" . mysqli_error($conn);
    }
} else {
    // Jika ada yang mencoba mengakses file ini langsung lewat URL tanpa submit form
    header("Location: index.php");
    exit();
}
?>