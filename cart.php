<?php
require 'Database/config.php';
require 'Logic/fuction.php';
require 'includes/header.php';

// Logika Hapus Item dari Keranjang
if(isset($_GET['remove'])) {
    $id_remove = $_GET['remove'];
    unset($_SESSION['cart'][$id_remove]);
    header("Location: cart.php");
    exit();
}
?>

<div class="container">
    <h2>Keranjang Belanja</h2>
    
    <?php if(empty($_SESSION['cart'])) : ?>
        <p>Keranjang Anda masih kosong. <a href="index.php">Kembali belanja</a>.</p>
    <?php else : ?>
        <table>
            <thead>
                <tr>
                    <th>Nama Menu</th>
                    <th>Harga</th>
                    <th>Qty</th>
                    <th>Subtotal</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $total_belanja = 0;
                foreach($_SESSION['cart'] as $id_menu => $qty) : 
                    $menu = getMenuById($conn, $id_menu);
                    $subtotal = $menu['price'] * $qty;
                    $total_belanja += $subtotal;
                ?>
                <tr>
                    <td><?= htmlspecialchars($menu['name']) ?></td>
                    <td><?= formatRupiah($menu['price']) ?></td>
                    <td><?= $qty ?></td>
                    <td><?= formatRupiah($subtotal) ?></td>
                    <td><a href="cart.php?remove=<?= $id_menu ?>" style="color: red;">Hapus</a></td>
                </tr>
                <?php endforeach; ?>
                <tr>
                    <th colspan="3" style="text-align: right;">Total Keseluruhan</th>
                    <th colspan="2"><?= formatRupiah($total_belanja) ?></th>
                </tr>
            </tbody>
        </table>
        
        <div style="display: flex; gap: 10px;">
            <a href="checkout.php?type=sample" class="btn-action" style="background: #e67e22; width: 200px; text-align:center;">Minta Sample</a>
            <a href="checkout.php?type=checkout" class="btn-action" style="width: 200px; text-align:center;">Lanjut Checkout</a>
        </div>
    <?php endif; ?>
</div>

</body>
</html>