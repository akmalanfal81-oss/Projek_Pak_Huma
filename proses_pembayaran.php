<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
// Jeda 2.5 detik untuk efek loading
sleep(2); 

// Gacha: 80% Sukses, 20% Gagal
$is_success = (rand(1, 10) <= 8) ? true : false;
header('Content-Type: application/json');

if ($is_success) {
    echo json_encode(['status' => 'success', 'message' => 'Pembayaran berhasil diverifikasi oleh sistem GoPay!']);
} else {
    echo json_encode(['status' => 'failed', 'message' => 'Saldo tidak mencukupi atau transaksi ditolak.']);
}
exit();
?>