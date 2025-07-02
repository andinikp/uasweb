<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: ../uasandini/login.php");
    exit();
}

require_once '../uasandini/koneksi.php';

$errors = [];

if (isset($_POST['simpan'])) {
    $no_surat   = mysqli_real_escape_string($conn, $_POST['no_surat']);
    $pengirim   = mysqli_real_escape_string($conn, $_POST['pengirim']);
    $perihal    = mysqli_real_escape_string($conn, $_POST['perihal']);
    $tgl_surat  = $_POST['tanggal_surat'];

    // File upload
    $fileName = $_FILES['file_pdf']['name'];
    $tmpName  = $_FILES['file_pdf']['tmp_name'];
    $fileSize = $_FILES['file_pdf']['size'];
    $fileExt  = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
    $allowed  = ['pdf'];

    if (!in_array($fileExt, $allowed)) {
        $errors[] = "Hanya file PDF yang diperbolehkan.";
    } elseif ($fileSize > 2 * 1024 * 1024) {
        $errors[] = "Ukuran file maksimal 2MB.";
    } else {
        $newName = uniqid('surat_', true) . '.' . $fileExt;
        $uploadPath = "../uploads/" . $newName;

        if (move_uploaded_file($tmpName, $uploadPath)) {
            $query = "INSERT INTO surat (no_surat, pengirim, perihal, tanggal_surat, file_pdf)
                      VALUES ('$no_surat', '$pengirim', '$perihal', '$tgl_surat', '$newName')";

            if (mysqli_query($conn, $query)) {
                header("Location: index.php?success=tambah");
                exit();
            } else {
                $errors[] = "Gagal menyimpan ke database.";
                unlink($uploadPath); // hapus file jika gagal insert
            }
        } else {
            $errors[] = "Gagal mengunggah file.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Surat</title>
    <style>
        body {
            font-family: sans-serif; 
            background-image: url('../uasandini/bg.png'); 
            background-size: cover; margin: 0; padding: 0;
        }
        .container {
            width: 600px; margin: 40px auto; 
            background: white; 
            padding: 30px; b
            order-radius: 8px; 
            box-shadow: 0 0 10px rgba(0,0,0,.1);
        }
        h2 {margin-top: 0;}
        input, textarea {
            width: 100%; 
            padding: 10px; 
            margin-bottom: 15px; 
            border: 1px solid #ccc; 
            border-radius: 5px;
        }
        button {
            background: #27ae60; 
            color: white; 
            padding: 10px 20px; 
            border: none; 
            border-radius: 5px; 
            cursor: pointer;
        }
        button:hover {background: #1e8449;}
        .back {
            text-decoration: none; 
            margin-top: 10px; 
            display: inline-block; 
            color: #3498db;
        }
        .error {
            background: #ffe6e6; 
            color: #c0392b; 
            padding: 10px; 
            margin-bottom: 15px; 
            border-radius: 5px;}
    </style>
</head>
<body>
<div class="container">
    <h2>Tambah Surat Masuk</h2>

    <?php if (!empty($errors)): ?>
        <div class="error">
            <?php foreach ($errors as $err) echo "<p>$err</p>"; ?>
        </div>
    <?php endif; ?>

    <form method="post" enctype="multipart/form-data">
        <label>No. Surat</label>
        <input type="text" name="no_surat" required>

        <label>Pengirim</label>
        <input type="text" name="pengirim" required>

        <label>Perihal</label>
        <textarea name="perihal" required></textarea>

        <label>Tanggal Surat</label>
        <input type="date" name="tanggal_surat" required>

        <label>Upload File (PDF)</label>
        <input type="file" name="file_pdf" accept="application/pdf" required>

        <button type="submit" name="simpan">Simpan</button>
    </form>

    <a href="index.php" class="back">&larr; Kembali ke Daftar Surat</a>
</div>
</body>
</html>
