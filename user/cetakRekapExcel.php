<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include '../services/connection.php';

if (!isset($_SESSION['user_id'])) {
    die("Akses ditolak. Silakan login terlebih dahulu.");
}

$user_id = $_SESSION['user_id'];

$query = "SELECT nama_aplikasi,
                 jenis_permasalahan,
                 COUNT(*) AS total_masuk,
                 SUM(CASE WHEN status_laporan = 'Selesai' THEN 1 ELSE 0 END) AS total_selesai,
                 ROUND(AVG(durasi), 2) AS rata_durasi,
                 nama_petugas
          FROM laporan";

$filter = [];
$filter[] = "user_id = '$user_id'";

$filter[] = "status_laporan = 'Selesai'";

if (!empty($_GET['nama_aplikasi'])) {
  $app = mysqli_real_escape_string($conn, $_GET['nama_aplikasi']);
  $filter[] = "nama_aplikasi = '$app'";
}

if (!empty($_GET['masalah'])) {
    $mslh = mysqli_real_escape_string($conn, $_GET['masalah']);
    $filter[] = "jenis_permasalahan = '$mslh'";
}

if (!empty($_GET['tanggal_mulai']) && !empty($_GET['tanggal_selesai'])) {
  $mulai = mysqli_real_escape_string($conn, $_GET['tanggal_mulai']);
  $akhir = mysqli_real_escape_string($conn, $_GET['tanggal_selesai']);
  $filter[] = "DATE(waktu_pelaporan) BETWEEN '$mulai' AND '$akhir'";
}

if (count($filter) > 0) {
  $query .= " WHERE " . implode(" AND ", $filter);
}

$query .= " GROUP BY nama_aplikasi,  jenis_permasalahan, nama_petugas ORDER BY nama_aplikasi ASC";

$result = mysqli_query($conn, $query);

header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=rekap_admin_$user_id.xls");
header("Pragma: no-cache");
header("Expires: 0");

echo "<table border='1'>
<thead>
<tr style='background:#d9d9d9; font-weight:bold; text-align:center;'>
    <th>Nama Aplikasi</th>
    <th>Jenis Permasalahan</th>
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
        <td>{$row['jenis_permasalahan']}</td>
        <td>{$row['total_masuk']}</td>
        <td>{$row['total_selesai']}</td>
        <td>{$durasiFormat}</td>
        <td>{$row['nama_petugas']}</td>
    </tr>";
}

echo "</tbody></table>";
?>
