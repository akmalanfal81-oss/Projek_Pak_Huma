<?php
// MULAI SESI DI ATAS
if (session_status() === PHP_SESSION_NONE) { session_start(); }
require 'Database/config.php';

$error_login = "";
$success_register = "";
$error_register = "";

// PROSES FORM HARUS SEBELUM RENDER HEADER
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // 1. PROSES REGISTRASI
    if (isset($_POST['register'])) {
        $name = mysqli_real_escape_string($conn, $_POST['reg_name']);
        $email = mysqli_real_escape_string($conn, $_POST['reg_email']);
        $password = password_hash($_POST['reg_password'], PASSWORD_DEFAULT); 
        $phone = mysqli_real_escape_string($conn, $_POST['reg_phone']);
        $address = mysqli_real_escape_string($conn, $_POST['reg_address']);
        
        $photo_path = ""; 
        
        if(isset($_FILES['reg_photo']) && $_FILES['reg_photo']['error'] == 0) {
            $ext = strtolower(pathinfo($_FILES['reg_photo']['name'], PATHINFO_EXTENSION));
            if (in_array($ext, ['jpg', 'jpeg', 'png', 'webp'])) {
                $photo_name = "profil_" . time() . "_" . uniqid() . "." . $ext;
                
                $upload_dir = __DIR__ . '/uploads/';
                if(!is_dir($upload_dir)) { mkdir($upload_dir, 0777, true); }
                
                $target_server = $upload_dir . $photo_name;
                $target_db = "uploads/" . $photo_name;

                if(move_uploaded_file($_FILES['reg_photo']['tmp_name'], $target_server)) {
                    $photo_path = $target_db;
                }
            }
        }

        $cek_email = mysqli_query($conn, "SELECT email FROM users WHERE email = '$email'");
        if (mysqli_num_rows($cek_email) > 0) {
            $error_register = "Email sudah digunakan! Silakan gunakan email lain.";
        } else {
            $sql = "INSERT INTO users (name, email, password, phone, address, photo) 
                    VALUES ('$name', '$email', '$password', '$phone', '$address', '$photo_path')";
            if(mysqli_query($conn, $sql)) {
                $success_register = "Pendaftaran berhasil! Silakan masuk (Login).";
            } else {
                $error_register = "Terjadi kesalahan sistem saat mendaftar.";
            }
        }
    }

    // 2. PROSES LOGIN
    if (isset($_POST['login'])) {
        $email = mysqli_real_escape_string($conn, $_POST['log_email']);
        $password = $_POST['log_password'];
        $sql = "SELECT * FROM users WHERE email = '$email'";
        $result = mysqli_query($conn, $sql);
        
        if($row = mysqli_fetch_assoc($result)) {
            if(password_verify($password, $row['password'])) {
                $_SESSION['user_id'] = $row['id'];
                $_SESSION['user_name'] = $row['name'];
                $_SESSION['user_phone'] = $row['phone'];
                $_SESSION['user_address'] = $row['address'];
                $_SESSION['user_photo'] = $row['photo'];
                
                // Redirect berjalan lancar karena belum ada struktur HTML yang dipanggil
                header("Location: index.php");
                exit();
            } else {
                $error_login = "Password yang Anda masukkan salah!";
            }
        } else {
            $error_login = "Email tidak ditemukan! Silakan daftar terlebih dahulu.";
        }
    }
}

// RENDER TAMPILAN HEADER (Panggil Paling Akhir)
require 'includes/header.php';
?>

<div class="auth-container">
    <div class="tab-header">
        <div class="tab-btn active" onclick="switchTab('login')" id="btn-login">Masuk (Login)</div>
        <div class="tab-btn" onclick="switchTab('register')" id="btn-register">Daftar Akun Baru</div>
    </div>

    <div class="tab-content active" id="content-login">
        <h2 class="auth-title">Selamat Datang Kembali</h2>
        
        <?php if($error_login) echo "<div class='alert alert-error'>$error_login</div>"; ?>
        <?php if($success_register) echo "<div class='alert alert-success'>$success_register</div>"; ?>

        <form action="login.php" method="POST">
            <input type="hidden" name="login" value="1">
            <div class="form-group">
                <label>Email Pemesan</label>
                <input type="email" name="log_email" required placeholder="Masukkan email Anda">
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="log_password" required placeholder="Masukkan password">
            </div>
            <button type="submit" class="btn-action w-100">Masuk</button>
        </form>
    </div>

    <div class="tab-content" id="content-register">
        <h2 class="auth-title">Form Pendaftaran</h2>
        
        <?php if($error_register) echo "<div class='alert alert-error'>$error_register</div>"; ?>

        <form action="login.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="register" value="1">
            <div class="form-group">
                <label>Atas Nama Pemesan / Instansi</label>
                <input type="text" name="reg_name" required placeholder="Nama Lengkap">
            </div>
            <div class="form-group">
                <label>Alamat Email</label>
                <input type="email" name="reg_email" required placeholder="email@contoh.com">
            </div>
            <div class="form-group">
                <label>Buat Password</label>
                <input type="password" name="reg_password" required placeholder="Minimal 6 karakter">
            </div>
            <div class="form-group">
                <label>Nomor WhatsApp</label>
                <input type="tel" name="reg_phone" required placeholder="Contoh: 081234567890">
            </div>
            <div class="form-group">
                <label>Alamat Pengiriman Default</label>
                <textarea name="reg_address" rows="3" required placeholder="Alamat lengkap tujuan pesanan"></textarea>
            </div>
            <div class="form-group">
                <label>Foto Profil <span class="label-optional">(Opsional)</span></label>
                <input type="file" name="reg_photo" accept="image/png, image/jpeg, image/webp" class="input-file-custom">
            </div>
            <button type="submit" class="btn-action btn-secondary w-100">Daftar Sekarang</button>
        </form>
    </div>
</div>

<footer class="site-footer">
    <p>&copy; <?= date('Y') ?> Krisna Jaya Catering. Sistem Pemesanan Online.</p>
</footer>

<script>
    function switchTab(tabName) {
        document.getElementById('content-login').classList.remove('active');
        document.getElementById('content-register').classList.remove('active');
        document.getElementById('btn-login').classList.remove('active');
        document.getElementById('btn-register').classList.remove('active');

        document.getElementById('content-' + tabName).classList.add('active');
        document.getElementById('btn-' + tabName).classList.add('active');
    }
    <?php if($error_register): ?> switchTab('register'); <?php endif; ?>
</script>

</body>
</html>