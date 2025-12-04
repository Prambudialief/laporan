<?php

use Dompdf\FrameDecorator\Page;

$aplikasi = [
    "Banda Aceh",
    "Medan",
    "Pekanbaru",
    "Padang",
    "Tanjung Pinang",
    "Jambi",
    "Bengkulu",
    "Palembang",
    "Pangkal Pinang",
    "Lampung", 
    "Jakarta",
    "Bandung",
    "Semarang",
    "Surabaya",
    "Denpasar",
    "Mataram",
    "Kupang",
    "Pontianak",
    "Banjarmasin",
    "Balikpapan",
    "Makassar",
    "Kendari",
    "Palu",
    "Gorontalo",
    "Manado",
    "Ternate",
    "Ambon",
    "Sorong",
    "Manokwari",
    "Biak",
    "Timika",
    "Jayapura",
    "Merauke",
    "Banten",
    "Natuna",
    "Mentawai",
    "Maumere",
    "Yogyakarta",
    "Tarakan",
    "Palangkaraya",
    "Balai Diklat",
    "Iusar"
];

$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$rowsPerPage = 10;

$start = ($page - 1) * $rowsPerPage;
$dataPage = array_slice($aplikasi, $start, $rowsPerPage);

header("Content-Type: text/csv");
header("Content-Disposition: attachment; filename=daftar_kansar.csv");

$output = fopen("php://output", "w");

// Header kolom
fputcsv($output, ['No', 'Daftar Kansar']);

// Isi data
$no = $start + 1;
foreach ($dataPage as $item) {
    fputcsv($output, [$no++, $item]);
}

fclose($output);
exit;
?>
