<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include '../services/connection.php';

require __DIR__ . '/../vendor/autoload.php';

use Dompdf\Dompdf;

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
if (!empty($_GET['nama_aplikasi'])) {
    $nama_aplikasi = mysqli_real_escape_string($conn, $_GET['nama_aplikasi']);
    $filter[] = "nama_aplikasi = '$nama_aplikasi'";
}

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

// Mulai PDF
$fontRegular = __DIR__ . '/../fonts/calibri-regular.ttf';
$fontBold    = __DIR__ . '/../fonts/calibri-bold.ttf';


$html = '
<style>
@font-face {
    font-family: "calibri";
    src: url("' . $fontRegular . '") format("truetype");
}
@font-face {
    font-family: "calibri";
    font-weight: bold;
    src: url("' . $fontBold . '") format("truetype");
}


body, table, th, td {
    font-family: "calibri", Arial, sans-serif;
    src: url("' . $fontRegular . '") format("truetype");
    font-size: 12px;
}

h2 {
    font-family: "calibri", Arial, sans-serif;
    src: url("' . $fontRegular . '") format("truetype");
}

table {
    border-collapse: collapse;
}

th {
    background: #f5f5f5;
    font-weight: bold;
}
</style>

<h2 style="text-align:center;">Daftar Laporan</h2>';

$html .= "</ul><br>";

$html .= '
<table border="1" cellspacing="0" cellpadding="5" width="100%">
<thead>
  <tr style="background:#f5f5f5; font-weight:bold;  font-family: Calibri, Candara, Segoe, Segoe UI, Optima, Arial, sans-serif;">
    <th>No</th>
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
<tbody>';
if ($result && $result->num_rows > 0) {
    $no = 1;
    while ($row = mysqli_fetch_assoc($result)) {

        // Format durasi
        $durasiMenit = (int)$row['durasi'];
        $jam = floor($durasiMenit / 60);
        $menit = $durasiMenit % 60;

        $durasiFormat = $jam > 0 ? "$jam jam $menit menit" : "$menit menit";

        $namaLanjuti = $row['nama_lanjuti'] ?: '-';

        $html .= "<tr>
                <td>{$no}</td>
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
                <td>{$durasiFormat}</td>
            </tr>";

        $no++; // ← NOMOR OTOMATIS
    }
}


$html .= "</tbody></table>";

$dompdf = new Dompdf([
    'enable_remote' => true,
    'enable_font_subsetting' => true
]);

$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'landscape');
$dompdf->render();

// LANGSUNG DOWNLOAD PDF
$dompdf->stream("daftar_laporan_viewer.pdf", ["Attachment" => true]);
exit;
?>
