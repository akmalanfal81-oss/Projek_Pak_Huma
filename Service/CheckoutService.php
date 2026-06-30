<?php
namespace Service;

use Models\TransactionModel;
use Models\MenuModel;

class CheckoutService {
    private $trxModel;
    private $menuModel;

    public function __construct(TransactionModel $trxModel, MenuModel $menuModel) {
        $this->trxModel = $trxModel;
        $this->menuModel = $menuModel;
    }

    // Fungsi utama pemrosesan logika bisnis yang bersih (Clean Code)
    public function processCheckout($customerName, $phone, $address, $specialReq, $orderType, $paymentMethod, $cartItems, $paketName) {
        
        // 1. Modifikasi Request Khusus Paket
        if ($orderType === 'paket' && !empty($paketName)) {
            $specialReq = "[PESANAN PAKET: " . strtoupper($paketName) . "] " . $specialReq;
        }

        // 2. Hitung Harga Dasar
        $basePrice = 0;
        if (!empty($cartItems)) {
            foreach ($cartItems as $menuId => $qty) {
                $menu = $this->menuModel->getMenuById($menuId);
                $basePrice += ($menu['price'] * $qty);
            }
        }

        // 3. Terapkan Design Pattern Strategy Berdasarkan Pilihan Pembayaran
        $paymentStrategy = null;
        if ($paymentMethod === 'QRIS') {
            $paymentStrategy = new \Service\QrisPayment();
        } else {
            $paymentStrategy = new \Service\BankTransferPayment(); // Bisa dikembangkan nanti
        }

        // Hitung total menggunakan rumus dari masing-masing Strategy
        $finalPrice = $paymentStrategy->calculateTotal($basePrice);

        // 4. Generate Kode & Simpan Transaksi Utama
        $trxCode = "TRX-" . strtoupper(substr(uniqid(), -5)) . rand(10, 99);
        $transactionId = $this->trxModel->createTransaction(
            $trxCode, $customerName, $phone, $address, $specialReq, $finalPrice, $orderType, $paymentMethod
        );

        // 5. Simpan Detail Item
        if ($transactionId) {
            foreach ($cartItems as $menuId => $qty) {
                $menu = $this->menuModel->getMenuById($menuId);
                $subtotal = $menu['price'] * $qty;
                $this->trxModel->createTransactionDetail($transactionId, $menuId, $qty, $subtotal);
            }
            return $transactionId; // Kembalikan ID transaksi jika berhasil
        }
        return false;
    }
}
?>