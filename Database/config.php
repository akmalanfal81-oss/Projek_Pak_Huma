<?php
$host = "localhost";
$user = "root";
$pass = "Trilytr246";
$db   = "krisnajaya_db"; // Hapus backslash

$conn = mysqli_connect($host, $user, $pass, $db); // Hapus semua backslash

if (!$conn) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}
?>