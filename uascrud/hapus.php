<?php
// /uascrud/hapus.php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: ../uasandini/login.php");
    exit();
}

require_once '../uasandini/koneksi.php';

// Pastikan ID valid
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: index.php?error=invalid_id");
    exit();
}

$id = (int) $_GET['id'];

// ── Ambil nama file PDF terlebih dahulu ─────────────────────────────
$stmt = mysqli_prepare($conn, "SELECT file_pdf FROM surat WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
mysqli_stmt_bind_result($stmt, $fileName);
mysqli_stmt_fetch($stmt);
mysqli_stmt_close($stmt);

if (!$fileName) {
    header("Location: index.php?error=not_found");
    exit();
}

// ── Hapus file PDF di /uploads (jika ada) ───────────────────────────
$filePath = "../uploads/" . $fileName;
if (file_exists($filePath)) {
    unlink($filePath);        // hapus file fisik
}

// ── Hapus data dari database ───────────────────────────────────────
$stmt = mysqli_prepare($conn, "DELETE FROM surat WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
mysqli_stmt_close($stmt);

// ── Selesai, kembali ke daftar surat ───────────────────────────────
header("Location: index.php?success=hapus");
exit();
?>
