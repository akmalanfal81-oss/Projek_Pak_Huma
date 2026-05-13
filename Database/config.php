<?php
$host = "localhost";
$user = "root";
$pass = "Trilytr246"; // Pastikan password ini memang benar password MySQL Anda
$db   = "krisnajaya_db"; // <-- Pastikan namanya krisnajaya_db

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}
?>