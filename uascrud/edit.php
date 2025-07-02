<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: ../uasandini/login.php");
    exit();
}

require_once '../uasandini/koneksi.php';

// Ambil data berdasarkan ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("ID tidak valid.");
}

$id = $_GET['id'];
$query = "SELECT * FROM surat WHERE id = $id";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) != 1) {
    die("Data tidak ditemukan.");
}

$data = mysqli_fetch_assoc($result);
$errors = [];

if (isset($_POST['update'])) {
    $no_surat  = mysqli_real_escape_string($conn, $_POST['no_surat']);
    $pengirim  = mysqli_real_escape_string($conn, $_POST['pengirim']);
    $perihal   = mysqli_real_escape_string($conn, $_POST['perihal']);
    $tgl_surat = $_POST['tanggal_surat'];

    $fileLama  = $data['file_pdf'];
    $fileBaru  = $_FILES['file_pdf']['name'];
    $newName   = $fileLama;

    // Jika user mengunggah file baru
    if (!empty($fileBaru)) {
        $tmpName = $_FILES['file_pdf']['tmp_name'];
        $fileExt = strtolower(pathinfo($fileBaru, PATHINFO_EXTENSION));
        $fileSize = $_FILES['file_pdf']['size'];
        $allowed = ['pdf'];

        if (!in_array($fileExt, $allowed)) {
            $errors[] = "File harus berupa PDF.";
        } elseif ($fileSize > 2 * 1024 * 1024) {
            $errors[] = "Ukuran file maksimal 2MB.";
        } else {
            $newName = uniqid('surat_', true) . '.' . $fileExt;
            $uploadPath = "../uploads/" . $newName;

            if (move_uploaded_file($tmpName, $uploadPath)) {
                // Hapus file lama
                if (file_exists("../uploads/" . $fileLama)) {
                    unlink("../uploads/" . $fileLama);
                }
            } else {
                $errors[] = "Gagal mengunggah file baru.";
            }
        }
    }

    // Jika tidak ada error, update database
    if (empty($errors)) {
        $update = "UPDATE surat SET
                    no_surat = '$no_surat',
                    pengirim = '$pengirim',
                    perihal = '$perihal',
                    tanggal_surat = '$tgl_surat',
                    file_pdf = '$newName'
                   WHERE id = $id";

        if (mysqli_query($conn, $update)) {
            header("Location: index.php?success=update");
            exit();
        } else {
            $errors[] = "Gagal menyimpan perubahan.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Surat</title>
    <style>
        body {
            font-family: sans-serif; 
            background-image: url('../uasandini/bg.png');
        }
        .container {
            width: 600px; 
            margin: 40px auto; 
            background: white; 
            padding: 30px; 
            border-radius: 8px; b
            ox-shadow: 0 0 10px rgba(0,0,0,.1);
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
            background: #e67e22; 
            color: white; padding: 10px 20px; 
            border: none; border-radius: 5px; 
            cursor: pointer;
        }
        button:hover {background: #d35400;}
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
            border-radius: 5px;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>Edit Surat</h2>

    <?php if (!empty($errors)): ?>
        <div class="error">
            <?php foreach ($errors as $err) echo "<p>$err</p>"; ?>
        </div>
    <?php endif; ?>

    <form method="post" enctype="multipart/form-data">
        <label>No. Surat</label>
        <input type="text" name="no_surat" value="<?= htmlspecialchars($data['no_surat']); ?>" required>

        <label>Pengirim</label>
        <input type="text" name="pengirim" value="<?= htmlspecialchars($data['pengirim']); ?>" required>

        <label>Perihal</label>
        <textarea name="perihal" required><?= htmlspecialchars($data['perihal']); ?></textarea>

        <label>Tanggal Surat</label>
        <input type="date" name="tanggal_surat" value="<?= $data['tanggal_surat']; ?>" required>

        <label>Ganti File PDF (opsional)</label>
        <input type="file" name="file_pdf" accept="application/pdf">
        <small>File lama: <?= htmlspecialchars($data['file_pdf']); ?></small>

        <button type="submit" name="update">Simpan Perubahan</button>
    </form>

    <a href="index.php" class="back">&larr; Kembali ke Daftar Surat</a>
</div>
</body>
</html>
