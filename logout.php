<?php
// Mulai sesi jika belum dimulai
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 1. Kosongkan semua variabel sesi
$_SESSION = array();

// 2. Jika Anda ingin menghancurkan sesi sepenuhnya, hapus juga cookie sesinya.
// Ini opsional tapi sangat disarankan untuk keamanan ekstra.
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// 3. Hancurkan sesi
session_destroy();

// 4. Arahkan kembali ke halaman beranda (index.php)
header("Location: index.php");
exit();
?>