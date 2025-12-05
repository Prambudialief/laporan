<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include '../services/connection.php';

$query = "SELECT nama_aplikasi, 
                 COUNT(*) AS total_masuk,
                 SUM(CASE WHEN status_laporan = 'Selesai' THEN 1 ELSE 0 END) AS total_selesai,
                 ROUND(AVG(durasi), 2) AS rata_durasi,
                 nama_petugas
          FROM laporan";

$filter = [];

if (!empty($_GET['judul_laporan'])) {
  $tgl = mysqli_real_escape_string($conn, $_GET['judul_laporan']);
  $filter[] = "DATE(waktu_pelaporan) = '$tgl'";
}

if (!empty($_GET['tahun'])) {
  $tahun = mysqli_real_escape_string($conn, $_GET['tahun']);
  $filter[] = "YEAR(waktu_pelaporan) = '$tahun'";
}

if (!empty($_GET['status_laporan'])) {
  $status = mysqli_real_escape_string($conn, $_GET['status_laporan']);
  $filter[] = "status_laporan = '$status'";
}

if (!empty($_GET['nama_aplikasi'])) {
  $app = mysqli_real_escape_string($conn, $_GET['nama_aplikasi']);
  $filter[] = "nama_aplikasi = '$app'";
}

if (!empty($_GET['petugas'])) {
  $petugas = mysqli_real_escape_string($conn, $_GET['petugas']);
  $filter[] = "nama_petugas LIKE '%$petugas%'";
}

if (!empty($_GET['bulan_mulai']) && !empty($_GET['bulan_selesai'])) {
  $mulai = $_GET['bulan_mulai'] . "-01";
  $akhir = date("Y-m-t", strtotime($_GET['bulan_selesai'] . "-01"));
  $filter[] = "DATE(waktu_pelaporan) BETWEEN '$mulai' AND '$akhir'";
}

if (count($filter) > 0) {
  $query .= " WHERE " . implode(" AND ", $filter);
}

$query .= " GROUP BY nama_aplikasi, nama_petugas ORDER BY nama_aplikasi ASC";

$result = mysqli_query($conn, $query);

header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=rekap_user_.xls");
header("Pragma: no-cache");
header("Expires: 0");

echo "<table border='1'>
<thead>
<tr style='background:#d9d9d9; font-weight:bold; text-align:center;'>
    <th>Nama Aplikasi</th>
    <th>Jumlah Masuk</th>
    <th>Selesai</th>
    <th>Rata Durasi</th>
    <th>Petugas</th>
</tr>
</thead>
<tbody>";

while ($row = mysqli_fetch_assoc($result)) {

    $durasi = (int)$row['rata_durasi'];
    $jam = floor($durasi / 60);
    $menit = $durasi % 60;

    if ($jam > 0) {
        $durasiFormat = $jam . " jam " . $menit . " menit";
    } else {
        $durasiFormat = $menit . " menit";
    }

    echo "<tr>
        <td>{$row['nama_aplikasi']}</td>
        <td>{$row['total_masuk']}</td>
        <td>{$row['total_selesai']}</td>
        <td>{$durasiFormat}</td>
        <td>{$row['nama_petugas']}</td>
    </tr>";
}

echo "</tbody></table>";
?>
