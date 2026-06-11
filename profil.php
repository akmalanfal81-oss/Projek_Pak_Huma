<?php
// 1. MULAI SESI PALING AWAL
if (session_status() === PHP_SESSION_NONE) { session_start(); }
require 'Database/config.php';

if(!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$pesan_sukses = "";
$pesan_error = "";

// 2. PROSES UPDATE HARUS DILAKUKAN SEBELUM MEMANGGIL HEADER
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_profil'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $password_baru = $_POST['password'];

    $query_update = "UPDATE users SET name='$name', email='$email', phone='$phone', address='$address'";

    if (!empty($password_baru)) {
        $hashed_password = password_hash($password_baru, PASSWORD_DEFAULT);
        $query_update .= ", password='$hashed_password'";
    }

    // LOGIKA UPLOAD FOTO YANG LEBIH KETAT
    $foto_baru = false;
    // Cek apakah user benar-benar memilih file gambar (Error 4 / UPLOAD_ERR_NO_FILE berarti tidak pilih file)
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] !== 4) { 
        if ($_FILES['photo']['error'] === 0) {
            $ext = strtolower(pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION));
            
            if (in_array($ext, ['jpg', 'jpeg', 'png', 'webp'])) {
                // Tambahkan uniqid() agar nama file tidak bentrok
                $photo_name = "profil_" . time() . "_" . uniqid() . "." . $ext; 
                $upload_dir = __DIR__ . '/uploads/';
                
                if (!is_dir($upload_dir)) { 
                    mkdir($upload_dir, 0777, true); 
                }
                
                $target_server = $upload_dir . $photo_name; 
                $target_db = "uploads/" . $photo_name;      
                
                if (move_uploaded_file($_FILES['photo']['tmp_name'], $target_server)) {
                    $query_update .= ", photo='$target_db'";
                    $foto_baru = $target_db;
                } else {
                    $pesan_error = "Gagal memindahkan file gambar. Pastikan folder uploads memiliki izin tulis.";
                }
            } else {
                $pesan_error = "Format gambar harus JPG, JPEG, PNG, atau WEBP.";
            }
        } else {
            $pesan_error = "Terjadi kesalahan sistem saat upload (Kode Error: " . $_FILES['photo']['error'] . "). Pastikan ukuran file tidak melebihi 2MB.";
        }
    }

    $query_update .= " WHERE id='$user_id'";

    // JIKA TIDAK ADA ERROR FOTO, JALANKAN UPDATE DATABASE
    if (empty($pesan_error)) {
        if (mysqli_query($conn, $query_update)) {
            // Update Session agar Navbar Header IKUT BERUBAH saat itu juga
            $_SESSION['user_name'] = $name;
            $_SESSION['user_phone'] = $phone;
            $_SESSION['user_address'] = $address;
            if ($foto_baru) {
                $_SESSION['user_photo'] = $foto_baru;
            }
            $pesan_sukses = "Profil Anda berhasil diperbarui!";
        } else {
            $pesan_error = "Gagal memperbarui profil. Email mungkin sudah dipakai.";
        }
    }
}

// 3. BARU KITA PANGGIL HEADER SETELAH SEMUA DATA SESI DIPERBARUI
require 'includes/header.php';

// 4. AMBIL DATA TERBARU UNTUK DITAMPILKAN DI FORM BAWAH
$query = mysqli_query($conn, "SELECT * FROM users WHERE id = '$user_id'");
$user = mysqli_fetch_assoc($query);

$path_di_server = __DIR__ . '/' . $user['photo'];
$foto_profil = (!empty($user['photo']) && file_exists($path_di_server)) 
    ? $user['photo'] . '?v=' . time() 
    : "https://ui-avatars.com/api/?name=" . urlencode($user['name']) . "&background=1e3a8a&color=fff&size=150";

?>

<div class="profile-container">
    <div class="profile-card">
        <form action="profil.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="update_profil" value="1">

            <div class="profile-header">
                <div class="avatar-wrapper" onclick="document.getElementById('file-upload').click();">
                    <img src="<?= htmlspecialchars($foto_profil) ?>" alt="Foto Profil" class="profile-avatar" id="image-preview">
                    <div class="avatar-overlay">
                        <span class="avatar-icon">📷</span>
                        Ubah Foto
                    </div>
                </div>
                <input type="file" id="file-upload" name="photo" accept="image/png, image/jpeg, image/webp" class="hidden-input" onchange="previewImage(event)">
                
                <h2 class="profile-name"><?= htmlspecialchars($user['name']) ?></h2>
                <p class="profile-role">Pengaturan Akun Pemesan</p>
            </div>

            <div class="profile-body">
                <?php if($pesan_sukses) echo "<div class='alert alert-success'>$pesan_sukses</div>"; ?>
                <?php if($pesan_error) echo "<div class='alert alert-error'>$pesan_error</div>"; ?>

                <div class="form-grid">
                    <div class="form-group">
                        <label>Nama Lengkap</label>
                        <input type="text" name="name" required value="<?= htmlspecialchars($user['name']) ?>">
                    </div>
                    <div class="form-group">
                        <label>Alamat Email</label>
                        <input type="email" name="email" required value="<?= htmlspecialchars($user['email']) ?>">
                    </div>
                    <div class="form-group">
                        <label>Nomor WhatsApp</label>
                        <input type="tel" name="phone" required value="<?= htmlspecialchars($user['phone']) ?>">
                    </div>
                    <div class="form-group">
                        <label>Password Baru <span class="label-optional">(Opsional)</span></label>
                        <input type="password" name="password" placeholder="Kosongkan jika tidak ingin diubah">
                    </div>
                </div>

                <div class="form-group">
                    <label>Alamat Pengiriman Utama</label>
                    <textarea name="address" rows="3" required><?= htmlspecialchars($user['address']) ?></textarea>
                </div>

                <hr class="divider" style="margin: 30px 0;">

                <div class="profile-actions">
                    <button type="submit" class="btn-action btn-save">💾 Simpan Perubahan</button>
                    <a href="logout.php" class="btn-danger btn-logout" onclick="return confirm('Apakah Anda yakin ingin keluar dari akun?');">Keluar Akun</a>
                </div>
            </div>
        </form>
    </div>
</div>

<footer class="site-footer">
    <p>&copy; <?= date('Y') ?> Krisna Jaya Catering. Sistem Pemesanan Online.</p>
</footer>

<script>
    function previewImage(event) {
        var reader = new FileReader();
        reader.onload = function(){
            var output = document.getElementById('image-preview');
            output.src = reader.result;
        };
        reader.readAsDataURL(event.target.files[0]);
    }
</script>

</body>
</html>