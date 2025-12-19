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

$q = trim($_GET['q'] ?? '');
if ($q !== '') {
    $q = mysqli_real_escape_string($conn, $q);
    $filter[] = "(
        nama_aplikasi LIKE '%$q%' OR
        nama_pelapor LIKE '%$q%' OR
        nama_petugas LIKE '%$q%' OR
        deskripsi_permasalahan LIKE '%$q%'
    )";
}
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
                  OR deskripsi_permasalahan LIKE '%$cari%'
                  OR kantor_sar LIKE '%$cari%'
                  OR unit_kerja LIKE '%$cari%')";
}

$status = $_GET['status'] ?? '';
if ($status !== '') {
    $status = mysqli_real_escape_string($conn, $status);
    $filter[] = "status_laporan = '$status'";
}

// Filter Rentang Bulan
if (!empty($_GET['tanggal_mulai']) && !empty($_GET['tanggal_selesai'])) {
    $mulai = mysqli_real_escape_string($conn, $_GET['tanggal_mulai']);
    $akhir = mysqli_real_escape_string($conn, $_GET['tanggal_selesai']);
    $filter[] = "DATE(waktu_pelaporan) BETWEEN '$mulai' AND '$akhir'";
}

$masalah = $_GET['masalah'] ?? '';
if ($masalah !== '') {
    $masalah = mysqli_real_escape_string($conn, $masalah);
    $filter[] = "jenis_permasalahan = '$masalah'";
}

// HEADER EXCEL
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=laporan_user_$user_id.xls");
header("Pragma: no-cache");
header("Expires: 0");

// QUERY
$query = "
SELECT 
    l.*,
    ml.nama_lanjuti
FROM laporan l
LEFT JOIN master_lanjuti ml 
    ON l.lanjuti_id = ml.id
";

if (count($filter) > 0) {
    $query .= " WHERE " . implode(" AND ", $filter);
}
$query .= " ORDER BY waktu_pelaporan DESC";

$result = mysqli_query($conn, $query);

// TABEL
echo "<table border='1'>
<thead>
<tr style='background:#d9d9d9; font-weight:bold; text-align:center;'>
    <th>Waktu Pelaporan</th>
    <th>Waktu Penyelesaian</th>
    <th>Nama Aplikasi</th>
    <th>Nama Pelapor</th>
    <th>Satker/Kantor Sar</th>
    <th>Unit Kerja</th>
    <th>Nama Petugas</th>
    <th>Nama Lanjuti</th>
    <th>Deskripsi Permasalahan</th>
    <th>Deskripsi Solusi</th>
    <th>Jenis Permasalahan</th>
    <th>Status</th>
    <th>Durasi</th>
</tr>
</thead>
<tbody>";

while ($row = mysqli_fetch_assoc($result)) {
    $durasiMenit = (int)$row['durasi'];
    $jam = floor($durasiMenit / 60);
    $menit = $durasiMenit % 60;

    $durasiFormat = $jam > 0 ? "$jam jam $menit menit" : "$menit menit";

    $namaLanjuti = $row['nama_lanjuti'] ?: '-';

    echo "<tr>
        <td>{$row['waktu_pelaporan']}</td>
        <td>{$row['tanggal_penyelesaian']}</td>
        <td>{$row['nama_aplikasi']}</td>
        <td>{$row['nama_pelapor']}</td>
        <td>{$row['kantor_sar']}</td>
        <td>{$row['unit_kerja']}</td>
        <td>{$row['nama_petugas']}</td>
        <td>{$namaLanjuti}</td>
        <td>{$row['deskripsi_permasalahan']}</td>
        <td>{$row['solusi_permasalahan']}</td>
        <td>{$row['jenis_permasalahan']}</td>
        <td>{$row['status_laporan']}</td>
        <td style='text-align:center;'>{$durasiFormat}</td>
    </tr>";
}

echo "</tbody></table>";
?>
