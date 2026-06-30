<?php
namespace Service;

// 1. Antarmuka (Interface) Strategy
interface PaymentStrategy {
    public function calculateTotal($basePrice);
    public function getPaymentInstruction($totalPayment);
}

// 2. Concrete Strategy A: QRIS
class QrisPayment implements PaymentStrategy {
    public function calculateTotal($basePrice) {
        $adminFee = 2500; // Biaya admin QRIS
        return $basePrice + $adminFee;
    }

    public function getPaymentInstruction($totalPayment) {
        return "Scan QRIS ini di aplikasi E-Wallet Anda untuk membayar Rp " . number_format($totalPayment, 0, ',', '.');
    }
}

// 3. Concrete Strategy B: Transfer Bank
class BankTransferPayment implements PaymentStrategy {
    public function calculateTotal($basePrice) {
        $adminFee = 0; // Transfer bank bebas biaya admin
        return $basePrice + $adminFee;
    }

    public function getPaymentInstruction($totalPayment) {
        return "Transfer ke Bank BCA 1234-5678-9012 a/n Krisna Jaya sebesar Rp " . number_format($totalPayment, 0, ',', '.');
    }
}
?>