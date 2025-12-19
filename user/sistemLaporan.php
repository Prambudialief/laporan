<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include '../services/connection.php';

if (isset($_POST['simpan'])) {

    // Ambil data session user
    $user_id = $_SESSION['user_id'];
    $nama_petugas = $_SESSION['nama'];

    // Ambil data POST
    $judul_laporan = $_POST['judul_laporan'];
    $nama_aplikasi = $_POST['nama_aplikasi'];
    $jenis_permasalahan = $_POST['jenis_permasalahan'];
    $kantor_sar = $_POST['kantor_sar'];
    $unit_kerja = $_POST['unit_kerja'];
    $nama_pelapor = $_POST['nama_pelapor'];
    $media_pelaporan = $_POST['media_pelaporan'];
    $waktu_pelaporan = $_POST['waktu_pelaporan'];
    $tanggal_pemutakhiran = $_POST['tanggal_pemutakhiran'];
    $deskripsi_permasalahan = $_POST['deskripsi_permasalahan'];
    $solusi_permasalahan = $_POST['solusi_permasalahan'];
    $status_laporan = $_POST['status_laporan'];
    $lanjuti_id = intval($_POST['lanjuti_id']);


    $folder = "../upload/";

    // Upload Gambar Deskripsi
    $gambar_deskripsi = null;
    if (!empty($_FILES['gambar_deskripsi']['name'])) {
        $ext1 = pathinfo($_FILES['gambar_deskripsi']['name'], PATHINFO_EXTENSION);
        $newName1 = uniqid("deskripsi_") . "." . $ext1;
        move_uploaded_file($_FILES['gambar_deskripsi']['tmp_name'], $folder . $newName1);
        $gambar_deskripsi = $newName1;
    }

    // Upload Gambar Solusi
    $gambar_solusi = null;
    if (!empty($_FILES['gambar_solusi']['name'])) {
        $ext2 = pathinfo($_FILES['gambar_solusi']['name'], PATHINFO_EXTENSION);
        $newName2 = uniqid("solusi_") . "." . $ext2;
        move_uploaded_file($_FILES['gambar_solusi']['tmp_name'], $folder . $newName2);
        $gambar_solusi = $newName2;
    }

    // Sanitasi teks
    $judul_laporan = mysqli_real_escape_string($conn, $judul_laporan);
    $nama_aplikasi = mysqli_real_escape_string($conn, $nama_aplikasi);
    $jenis_permasalahan = mysqli_real_escape_string($conn, $jenis_permasalahan);
    $kantor_sar = mysqli_real_escape_string($conn, $kantor_sar);
    $unit_kerja = mysqli_real_escape_string($conn, $unit_kerja);
    $nama_pelapor = mysqli_real_escape_string($conn, $nama_pelapor);
    $media_pelaporan = mysqli_real_escape_string($conn, $media_pelaporan);
    $deskripsi_permasalahan = mysqli_real_escape_string($conn, $deskripsi_permasalahan);
    $solusi_permasalahan = mysqli_real_escape_string($conn, $solusi_permasalahan);
    $status_laporan = mysqli_real_escape_string($conn, $status_laporan);
    $lanjuti_id = intval($lanjuti_id);


    // Query Insert
    $query = "INSERT INTO laporan (
        user_id, lanjuti_id, judul_laporan, nama_aplikasi, kantor_sar, nama_pelapor,
        media_pelaporan, waktu_pelaporan, nama_petugas, tanggal_pemutakhiran,
        deskripsi_permasalahan, gambar_deskripsi, solusi_permasalahan, gambar_solusi,
        status_laporan, jenis_permasalahan, unit_kerja
    ) VALUES (
        '$user_id', '$lanjuti_id', '$judul_laporan', '$nama_aplikasi', '$kantor_sar', '$nama_pelapor',
        '$media_pelaporan', '$waktu_pelaporan', '$nama_petugas', '$tanggal_pemutakhiran',
        '$deskripsi_permasalahan', '$gambar_deskripsi', '$solusi_permasalahan', '$gambar_solusi',
        '$status_laporan', '$jenis_permasalahan', '$unit_kerja'
    )";

    if (mysqli_query($conn, $query)) {
        echo "<script>alert('Laporan berhasil disimpan!'); window.location='daftar_laporan.php';</script>";
    } else {
        echo "<script>alert('Gagal menyimpan laporan: " . mysqli_error($conn) . "');</script>";
    }
}
?>
