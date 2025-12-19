<?php
if (session_status()===PHP_SESSION_NONE) {
  session_start();
}
include '../services/connection.php';

require __DIR__ . '/../vendor/autoload.php';

use Dompdf\Dompdf;

$query = "SELECT nama_aplikasi,
                 jenis_permasalahan,
                 COUNT(*) AS total_masuk,
                 SUM(CASE WHEN status_laporan = 'Selesai' THEN 1 ELSE 0 END) AS total_selesai,
                 ROUND(AVG(durasi), 2) AS rata_durasi,
                 nama_petugas
          FROM laporan";
$filter = [];

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

$query .= " GROUP BY nama_aplikasi, jenis_permasalahan, nama_petugas ORDER BY nama_aplikasi ASC";

$result = mysqli_query($conn, $query);

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

<h2 style="text-align:center;">Daftar Rekapitulasi</h2>';

$html .= "<table border='1' cellspacing='0' cellpadding='5' width='100%'>
<thead>
  <tr style='background:#f0f0f0;'>
    <th>No</th>
    <th>Nama Aplikasi</th>
    <th>Jenis Permasalahan</th>
    <th>Jumlah Laporan Masuk</th>
    <th>Laporan Selesai</th>
    <th>Rata-Rata Durasi</th>
    <th>Petugas</th>
  </tr>
</thead>
<tbody>";

if ($result && $result->num_rows > 0) {
  $no = 1;
  while ($row = mysqli_fetch_assoc($result)) {
    $durasi = (int)$row['rata_durasi'];
    $jam = floor($durasi / 60);
    $menit = $durasi % 60;

    if ($jam > 0) {
      $durasiFormat = $jam . " jam " . $menit . " menit";
    } else {
      $durasiFormat = $menit . " menit";
    }

    $html .= "
  <tr>
      <td>{$no}</td>
      <td>{$row['nama_aplikasi']}</td>
      <td>{$row['jenis_permasalahan']}</td>
      <td>{$row['total_masuk']}</td>
      <td>{$row['total_selesai']}</td>
      <td>{$durasiFormat}</td>
      <td>{$row['nama_petugas']}</td>
  </tr>
  ";
  $no++;
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
$dompdf->stream("rekap_admin.pdf", ["Attachment" => true]);
exit;

?>
