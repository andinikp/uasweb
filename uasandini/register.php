<?php
session_start();
require_once 'koneksi.php';

/* ── Jika sudah login, alihkan ke dashboard ─ */
if (isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

/* ── Proses registrasi ─ */
if (isset($_POST['register'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];
    $konfirmasi = $_POST['konfirmasi'];

    // Validasi input tidak boleh kosong
    if (empty($username) || empty($password) || empty($konfirmasi)) {
        $error = "Semua field wajib diisi!";
    } elseif ($password !== $konfirmasi) {
        $error = "Konfirmasi password tidak sesuai!";
    } else {
        // Cek apakah username sudah ada
        $cek = mysqli_query($conn, "SELECT * FROM users WHERE username = '$username'");
        if (mysqli_num_rows($cek) > 0) {
            $error = "Username sudah digunakan!";
        } else {
            // Simpan user baru (gunakan password_hash untuk keamanan)
            $hashed_password = $password; // <-- Ganti dengan password_hash($password, PASSWORD_DEFAULT); jika ingin di-hash

            $query = "INSERT INTO users (username, password) VALUES ('$username', '$hashed_password')";
            if (mysqli_query($conn, $query)) {
                $success = "Akun berhasil dibuat! Silakan login.";
            } else {
                $error = "Gagal menyimpan data: " . mysqli_error($conn);
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Register ‑ Disposisi Surat</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            background-image: url('bg.png');
            background-size: cover;
            background-position: center;
            margin: 0;
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .register-box{
            background: transparent;
            padding:30px 40px;
            border-radius:25px;
            box-shadow:0 0 10px rgba(0,0,0,0.3);
            width: 100%;
            max-width: 300px;
            display: flex;
            flex-direction: column;
            justify-content: space;
            align-items: center;
            text-align:center
        }
        h1{margin:0 0 25px}
        input[type=text],input[type=password]{
            width:90%;padding:10px;margin-bottom:15px;border:1px solid #ccc;
            border-radius:5px
        }
        button{
            background:#2ecc71;color:#fff;border:none;padding:10px 25px;
            border-radius:5px;cursor:pointer
        }
        button:hover{background:#27ae60}
        .error{color:#e74c3c;margin-bottom:15px}
        .success{color:#2ecc71;margin-bottom:15px}
    </style>
</head>
<body>
<div class="register-box">
    <h1>DAFTAR</h1>

    <?php 
    if (isset($error)) echo "<div class='error'>$error</div>";
    if (isset($success)) echo "<div class='success'>$success</div>";
    ?>

    <form method="post">
        <input type="text" name="username" placeholder="Username" required autofocus>
        <input type="password" name="password" placeholder="Password" required>
        <input type="password" name="konfirmasi" placeholder="Konfirmasi Password" required>
        <button type="submit" name="register">Daftar</button>
        <p>Sudah punya akun? <a href="login.php">Login di sini</a></p>
    </form>
</div>
</body>
</html>
