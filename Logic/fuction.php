<?php
// Fungsi untuk format mata uang Rupiah
function formatRupiah($angka){
    return "Rp " . number_format($angka, 0, ',', '.');
}

// Fungsi mengambil semua menu beserta kategorinya
function getAllMenus($conn) {
    $sql = "SELECT m.*, c.name as category_name FROM menus m JOIN categories c ON m.category_id = c.id";
    $result = mysqli_query($conn, $sql);
    $menus = [];
    while($row = mysqli_fetch_assoc($result)) {
        $menus[] = $row;
    }
    return $menus;
}

// Fungsi mengambil detail 1 menu berdasarkan ID
function getMenuById($conn, $id) {
    $sql = "SELECT * FROM menus WHERE id = $id";
    $result = mysqli_query($conn, $sql);
    return mysqli_fetch_assoc($result);
}
?>