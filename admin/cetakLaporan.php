<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include '../services/connection.php';

require __DIR__ . '/../vendor/autoload.php';

use Dompdf\Dompdf;

$filter = [];

if (!empty($_GET['nama_aplikasi'])) {
    $nama_aplikasi = mysqli_real_escape_string($conn, $_GET['nama_aplikasi']);
    $filter[] = "nama_aplikasi = '$nama_aplikasi'";
}

if (!empty($_GET['cari'])) {
    $cari = mysqli_real_escape_string($conn, $_GET['cari']);
    $filter[] = "(nama_pelapor LIKE '%$cari%' 
                  OR nama_petugas LIKE '%$cari%' 
                  OR deskripsi_permasalahan LIKE '%$cari%')";
}

if (!empty($_GET['bulan_mulai']) && !empty($_GET['bulan_selesai'])) {
    $mulai = $_GET['bulan_mulai'] . "-01";
    $akhir = date("Y-m-t", strtotime($_GET['bulan_selesai'] . "-01"));
    $filter[] = "DATE(waktu_pelaporan) BETWEEN '$mulai' AND '$akhir'";
}

$query = "SELECT * FROM laporan";
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


$html .= "<p><strong>Filter aktif:</strong></p><ul>";

if (!empty($_GET['nama_aplikasi'])) $html .= "<li>Aplikasi: $nama_aplikasi</li>";
if (!empty($_GET['cari'])) $html .= "<li>Pencarian: $cari</li>";
if (!empty($_GET['bulan_mulai']) && !empty($_GET['bulan_selesai']))
    $html .= "<li>Periode: {$_GET['bulan_mulai']} s/d {$_GET['bulan_selesai']}</li>";

$html .= "</ul><br>";

$html .= '
<table border="1" cellspacing="0" cellpadding="5" width="100%">
<thead>
  <tr style="background:#f5f5f5; font-weight:bold;  font-family: Calibri, Candara, Segoe, Segoe UI, Optima, Arial, sans-serif;">
    <th>No</th>
    <th>Tanggal Pelaporan</th>
    <th>Nama Aplikasi</th>
    <th>Nama Pelapor</th>
    <th>Kantor SAR</th>
    <th>Nama Petugas</th>
    <th>Deskripsi Permasalahan</th>
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

        $html .= "<tr>
                <td>{$no}</td>
                <td>{$row['waktu_pelaporan']}</td>
                <td>{$row['nama_aplikasi']}</td>
                <td>{$row['nama_pelapor']}</td>
                <td>{$row['kantor_sar']}</td>
                <td>{$row['nama_petugas']}</td>
                <td>{$row['deskripsi_permasalahan']}</td>
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

// Menampilkan PDF di browser (bukan download otomatis)
$dompdf->stream("laporan_filtered.pdf", ["Attachment" => false]);
