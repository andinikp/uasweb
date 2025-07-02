<?php
session_start();

// Cek apakah user sudah login
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Disposisi Surat</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.0/css/all.min.css">
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-image: url('bg.png');
            margin: 0;
            padding: 0;
        }
        header {
            background-color: #34495e;
            color: white;
            padding: 20px;
            text-align: center;
        }
        .logo {
            width: 60px;
            vertical-align: middle;
        }
        nav {
            text-align: center;
            margin: 30px 0;
        }
        nav a {
            text-decoration: none;
            background-color: #3498db;
            color: white;
            padding: 14px 25px;
            border-radius: 5px;
            margin: 0 10px;
            transition: background 0.3s ease;
        }
        nav a:hover {
            background-color: #2980b9;
        }
        .footer {
            text-align: center;
            color: #777;
            margin-top: 40px;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <header>
        <img src="disposisi.png" alt="Logo" class="logo">
        <h1>Selamat Datang, <?= htmlspecialchars($_SESSION['username']); ?></h1>
        <p>Aplikasi Disposisi Surat</p>
    </header>

    <nav>
        <a href="../uascrud/index.php"><i class="fa fa-folder-open"></i> Kelola Surat</a>
        <a href="logout.php"><i class="fa fa-sign-out-alt"></i> Logout</a>
    </nav>

    <div class="footer">
        &copy; <?= date('Y'); ?> Aplikasi Disposisi Surat
    </div>
</body>
</html>