<?php
session_start();
require_once 'koneksi.php';

/* ── Jika sudah login, langsung ke dashboard ───────────────────────── */
if (isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

/* ── Proses login ketika tombol “Masuk” ditekan ────────────────────── */
if (isset($_POST['login'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];

    // Ambil data user
    $query  = "SELECT * FROM users WHERE username = '$username' LIMIT 1";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) === 1) {
        $user = mysqli_fetch_assoc($result);

        // Cek password (non‑hash; ganti dengan password_verify() jika di‑hash)
        if ($user['password'] === $password) {
            $_SESSION['username'] = $user['username'];
            header("Location: index.php");
            exit();
        } else {
            $error = "Password salah!";
        }
    } else {
        $error = "Username tidak ditemukan!";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <link rel="stylesheet" href="style.css">
    <meta charset="UTF-8">
    <title>Login ‑ Disposisi Surat</title>
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
        .login-box{
            background:transparent;
            padding:30px 35px;
            border-radius:25px;
            box-shadow:0 0 10px rgba(0,0,0,0.3);
            width: 100%;
            max-width: 300px;
            display: flex;
            flex-direction: column;
            justify-content: space;
            align-items: center;
            text-align:center;
        }
        h1{margin:0 0 25px}
        input[type=text],input[type=password]{
            width:90%;padding:10px;margin-bottom:15px;border:1px solid #ccc;
            border-radius:5px
        }
        button{
            background:#3498db;color:#fff;border:none;padding:10px 25px;
            border-radius:5px;cursor:pointer
        }
        button:hover{background:#2980b9}
        .error{color:#e74c3c;margin-bottom:15px}
    </style>
</head>
<body>
<div class="login-box">
    <h1>LOGIN</h1>

    <?php if (isset($error)) echo "<div class='error'>$error</div>"; ?>

    <form method="post">
        <input type="text" name="username" placeholder="Username" required autofocus>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit" name="login">Masuk</button>
        <p>Belum punya akun?
                <a href="register.php">Daftar disini</a>
        </p>
    </form>
</div>
</body>
</html>

