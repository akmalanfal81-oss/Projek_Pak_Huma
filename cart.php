<?php
require 'Database/config.php';
require 'Logic/fuction.php';
require 'includes/header.php';

// LOGIKA TAMBAH, KURANG, DAN HAPUS
if(isset($_GET['action']) && isset($_GET['id'])) {
    $id = $_GET['id'];
    if($_GET['action'] == 'plus') $_SESSION['cart'][$id]++;
    elseif ($_GET['action'] == 'minus') {
        $_SESSION['cart'][$id]--;
        if($_SESSION['cart'][$id] <= 0) unset($_SESSION['cart'][$id]);
    } elseif ($_GET['action'] == 'remove') {
        unset($_SESSION['cart'][$id]);
    }
    header("Location: cart.php");
    exit();
}
?>

<div class="container">
    <h2>Keranjang Belanja</h2>
    <?php if(empty($_SESSION['cart'])) : ?>
        <p>Keranjang Anda masih kosong. <a href="index.php" class="cart-empty-text">Kembali belanja</a>.</p>
    <?php else : ?>
        <form action="checkout.php?type=checkout" method="POST">
            <table class="cart-table">
                <thead>
                    <tr>
                        <th>Pilih</th>
                        <th>Nama Menu</th>
                        <th>Harga</th>
                        <th>Qty</th>
                        <th>Subtotal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($_SESSION['cart'] as $id_menu => $qty) :
                        $menu = getMenuById($conn, $id_menu);
                        $subtotal = $menu['price'] * $qty;
                    ?>
                    <tr>
                        <td><input type="checkbox" name="selected_items[]" value="<?= $id_menu ?>" checked></td>
                        <td><?= htmlspecialchars($menu['name']) ?></td>
                        <td><?= formatRupiah($menu['price']) ?></td>
                        <td>
                            <a href="cart.php?action=minus&id=<?= $id_menu ?>" class="qty-btn">-</a>
                            <span style="margin: 0 10px; font-weight: bold;"><?= $qty ?></span>
                            <a href="cart.php?action=plus&id=<?= $id_menu ?>" class="qty-btn">+</a>
                        </td>
                        <td><?= formatRupiah($subtotal) ?></td>
                        <td><a href="cart.php?action=remove&id=<?= $id_menu ?>" class="text-danger">Hapus</a></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <button type="submit" class="btn-action w-100">Checkout Menu Terpilih</button>
        </form>
    <?php endif; ?>
</div>
<?php require 'includes/footer.php'; ?>