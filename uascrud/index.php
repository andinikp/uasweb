<?php
// /uascrud/index.php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: ../uasandini/login.php");
    exit();
}

require_once '../uasandini/koneksi.php';

// Ambil seluruh surat
$sql   = "SELECT * FROM surat ORDER BY tanggal_upload DESC";
$hasil = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kelola Surat – Disposisi Surat</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.0/css/all.min.css">
    <style>
        body{font-family:sans-serif;
            background-image:url('../uasandini/bg.png');
            margin:0
        }
        .container{
            max-width:1000px;
            margin:40px auto;
            background:#fff;
            border-radius:8px;padding:25px;box-shadow:0 0 10px rgba(0,0,0,.1)
        }
        h2{margin-top:0
        }
        a.btn{
            display:inline-block;padding:8px 16px;
            border-radius:5px;text-decoration:none;
            color:#fff
        }
        .btn-blue{background:#3498db}
        .btn-green{background:#27ae60}
        .btn-red{background:#e74c3c}
        .btn-blue:hover{background:#2980b9}.btn-green:hover{background:#1e874e}.btn-red:hover{background:#c0392b}
        table{
            width:100%;
            border-collapse:collapse;
            margin-top:20px}
        th,td{
            padding:10px;
            border:1px solid #ddd;
            text-align:center
        }
        th{
            background:#34495e;color:#fff
        }
        tr:nth-child(even){background:#f2f2f2}
        .action i{margin:0 5px}
        .nav{margin-bottom:15px}
        .nav a{margin-right:10px}
    </style>
    <script>
        function confirmHapus(id){
            if(confirm("Yakin mau hapus surat ini?")){
                window.location='hapus.php?id='+id;
            }
        }
    </script>
</head>
<body>
<div class="container">
    <h2>Daftar Surat Masuk</h2>

    <!-- Navigasi -->
    <div class="nav">
        <a href="tambah.php" class="btn btn-green"><i class="fa fa-plus"></i> Tambah Surat</a>
        <a href="../uasandini/index.php" class="btn btn-blue"><i class="fa fa-home"></i> Dashboard</a>
        <a href="../uasandini/logout.php" class="btn btn-red"><i class="fa fa-sign-out-alt"></i> Logout</a>
    </div>

    <?php if(mysqli_num_rows($hasil) > 0): ?>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>No. Surat</th>
                <th>Pengirim</th>
                <th>Perihal</th>
                <th>Tgl. Surat</th>
                <th>File</th>
                <th>Opsi</th>
            </tr>
        </thead>
        <tbody>
        <?php $no=1; while($row = mysqli_fetch_assoc($hasil)): ?>
            <tr>
                <td><?= $no++; ?></td>
                <td><?= htmlspecialchars($row['no_surat']); ?></td>
                <td><?= htmlspecialchars($row['pengirim']); ?></td>
                <td><?= htmlspecialchars($row['perihal']); ?></td>
                <td><?= date('d-m-Y', strtotime($row['tanggal_surat'])); ?></td>
                <td>
                    <a href="../uploads/<?= urlencode($row['file_pdf']); ?>" target="_blank" class="btn btn-blue">
                        <i class="fa fa-file-pdf"></i> Lihat
                    </a>
                </td>
                <td class="action">
    <a href="edit.php?id=<?= $row['id']; ?>" class="btn btn-green" title="Edit">
        <i class="fa fa-edit"></i> Edit
    </a>
    <a href="javascript:void(0);" onclick="confirmHapus(<?= $row['id']; ?>)" class="btn btn-red" title="Hapus">
        <i class="fa fa-trash-alt"></i> Hapus
    </a>
</td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
    <?php else: ?>
        <p>Belum ada surat masuk.</p>
    <?php endif; ?>
</div>
</body>
</html>
