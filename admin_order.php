<?php
require 'Database/config.php';
require 'includes/header.php'; // Pastikan header mendukung level admin

// Ambil semua transaksi
$query = "SELECT * FROM transactions ORDER BY created_at DESC";
$result = mysqli_query($conn, $query);

// Proses update status jika tombol diklik
if(isset($_POST['update_status'])) {
    $id = mysqli_real_escape_string($conn, $_POST['trx_id']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);
    
    $update_sql = "UPDATE transactions SET status = '$status' WHERE id = '$id'";
    mysqli_query($conn, $update_sql);
    
    header("Location: admin_order.php");
    exit();
}
?>

<div class="container">
    <h2>Manajemen Pesanan (Admin)</h2>
    <table class="cart-table">
        <thead>
            <tr>
                <th>Kode</th>
                <th>Nama</th>
                <th>Status Saat Ini</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php while($row = mysqli_fetch_assoc($result)): 
                $badge_class = "badge-warning";
                if ($row['status'] == 'Dalam Proses') $badge_class = "badge-info";
                elseif ($row['status'] == 'Dikirim') $badge_class = "badge-info";
                elseif ($row['status'] == 'Selesai') $badge_class = "badge-success";
            ?>
            <tr>
                <td><?= htmlspecialchars($row['trx_code']) ?></td>
                <td><?= htmlspecialchars($row['customer_name']) ?></td>
                <td><span class="badge <?= $badge_class ?>"><?= htmlspecialchars($row['status']) ?></span></td>
                <td>
                    <form method="POST">
                        <input type="hidden" name="trx_id" value="<?= $row['id'] ?>">
                        <select name="status" class="form-group" style="padding: 5px;">
                            <option value="Menunggu Konfirmasi Admin" <?= $row['status']=='Menunggu Konfirmasi Admin'?'selected':'' ?>>Menunggu</option>
                            <option value="Dalam Proses" <?= $row['status']=='Dalam Proses'?'selected':'' ?>>Dalam Proses</option>
                            <option value="Dikirim" <?= $row['status']=='Dikirim'?'selected':'' ?>>Dikirim</option>
                            <option value="Selesai" <?= $row['status']=='Selesai'?'selected':'' ?>>Selesai</option>
                        </select>
                        <button type="submit" name="update_status" class="btn-action" style="padding: 5px 10px;">Update</button>
                    </form>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<footer class="site-footer">
    <p>&copy; <?= date('Y') ?> Krisna Jaya Catering. Sistem Pemesanan Online.</p>
</footer>
</body>
</html>