<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include '../services/connection.php';

$rowsPerPage = 10;
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
if ($page < 1) $page = 1;

$offset = ($page - 1) * $rowsPerPage;

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=laporan_permasalahan_page_' . $page . '.csv');

$output = fopen("php://output", "w");

fputcsv($output, [
    'No',
    'Jenis Permasalahan',
    'Nama Aplikasi'
]);


$query = "SELECT jenis_permasalahan, nama_aplikasi 
          FROM laporan 
          LIMIT $offset, $rowsPerPage";

$result = mysqli_query($conn, $query);

$no = $offset + 1;

while ($row = mysqli_fetch_assoc($result)) {
    fputcsv($output, [
        $no++,
        $row['jenis_permasalahan'],
        $row['nama_aplikasi']
    ]);
}

fclose($output);
exit();
?>
