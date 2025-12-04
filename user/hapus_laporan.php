<?php
include '../services/connection.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Hapus file upload (jika ada)
    $res = $conn->query("SELECT gambar_deskripsi, gambar_solusi FROM laporan WHERE id='$id'");
    $row = $res->fetch_assoc();
    if (!empty($row['gambar_deskripsi']) && file_exists("../upload/" . $row['gambar_deskripsi'])) {
        unlink("../upload/" . $row['gambar_deskripsi']);
    }
    if (!empty($row['gambar_solusi']) && file_exists("../upload/" . $row['gambar_solusi'])) {
        unlink("../upload/" . $row['gambar_solusi']);
    }

    // Hapus dari database
    $conn->query("DELETE FROM laporan WHERE id='$id'");

    echo "<script>alert('Laporan berhasil dihapus!'); window.location='daftar_laporan.php';</script>";
} else {
    echo "<script>alert('ID laporan tidak ditemukan!'); window.location='daftar_laporan.php';</script>";
}
?>
