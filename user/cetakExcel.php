<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include '../services/connection.php';

if (!isset($_SESSION['user_id'])) {
    die("Akses ditolak. Silakan login terlebih dahulu.");
}

$user_id = $_SESSION['user_id'];
$filter = [];

// Filter user
if ($_SESSION['role'] === 'user') {
    $filter[] = "user_id = " . intval($user_id);
}

// Filter Nama Aplikasi
if (!empty($_GET['nama_aplikasi'])) {
    $nama_aplikasi = mysqli_real_escape_string($conn, $_GET['nama_aplikasi']);
    $filter[] = "nama_aplikasi = '$nama_aplikasi'";
}

// Filter Pencarian
if (!empty($_GET['cari'])) {
    $cari = mysqli_real_escape_string($conn, $_GET['cari']);
    $filter[] = "(nama_pelapor LIKE '%$cari%' 
                  OR nama_petugas LIKE '%$cari%' 
                  OR deskripsi_permasalahan LIKE '%$cari%')";
}

// Filter Rentang Bulan
if (!empty($_GET['bulan_mulai']) && !empty($_GET['bulan_selesai'])) {
    $mulai = $_GET['bulan_mulai'] . "-01";
    $akhir = date("Y-m-t", strtotime($_GET['bulan_selesai'] . "-01"));
    $filter[] = "DATE(waktu_pelaporan) BETWEEN '$mulai' AND '$akhir'";
}

// HEADER EXCEL
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=laporan_user_$user_id.xls");
header("Pragma: no-cache");
header("Expires: 0");

// QUERY
$query = "SELECT * FROM laporan";
if (count($filter) > 0) {
    $query .= " WHERE " . implode(" AND ", $filter);
}
$query .= " ORDER BY waktu_pelaporan DESC";

$result = mysqli_query($conn, $query);

// TABEL
echo "<table border='1'>
<thead>
<tr style='background:#d9d9d9; font-weight:bold; text-align:center;'>
    <th>Tanggal Pelaporan</th>
    <th>Nama Aplikasi</th>
    <th>Nama Pelapor</th>
    <th>Kantor SAR</th>
    <th>Nama Petugas</th>
    <th>Deskripsi Permasalahan</th>
    <th>Jenis Permasalahan</th>
    <th>Status</th>
    <th>Durasi (Menit)</th>
</tr>
</thead>
<tbody>";

while ($row = mysqli_fetch_assoc($result)) {
    $durasiMenit = (int)$row['durasi'];
    $jam = floor($durasiMenit / 60);
    $menit = $durasiMenit % 60;

    $durasiFormat = $jam > 0 ? "$jam jam $menit menit" : "$menit menit";
    echo "<tr>
        <td>{$row['waktu_pelaporan']}</td>
        <td>{$row['nama_aplikasi']}</td>
        <td>{$row['nama_pelapor']}</td>
        <td>{$row['kantor_sar']}</td>
        <td>{$row['nama_petugas']}</td>
        <td>{$row['deskripsi_permasalahan']}</td>
        <td>{$row['jenis_permasalahan']}</td>
        <td>{$row['status_laporan']}</td>
        <td style='text-align:center;'>{$durasiFormat}</td>
    </tr>";
}

echo "</tbody></table>";
?>
