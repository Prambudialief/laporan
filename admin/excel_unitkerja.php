<?php
$aplikasi = [
   "Humas",
                    "Pusdatin",
                    "ULP",
                    "Kepegawain",
                    "Perencanaan",
                    "Biro Hukum & Kepegawain",
                    "Dit. Operasi dan Dit. Kesiapsiagaan",
                    "Biro Umum",
                    "Inspektorat",
                    "Balai Diklat & Puslat SDM",
                    "Dit Kesiapsiagaan",
                    "Biro Humas dan Umum",
                    "Dit. Binpot",
                    "Dit. Operasi",
                    "Dit. Komunikasi",
                    "Perencanaan KTLN",
                    "Biro Kepegawaian & Ortala",
                    "Dharma Wanita Persatuan",
                    "Deputi Bidang Operasi Pencarian dan Pertolongan, dan kesiapsiagaan"
];
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$rowsPerPage = 10;

$start = ($page - 1) * $rowsPerPage;
$dataPage = array_slice($aplikasi, $start, $rowsPerPage);

header("Content-Type: text/csv");
header("Content-Disposition: attachment; filename=daftar_unit_kerja.csv");

$output = fopen("php://output", "w");

// Header kolom
fputcsv($output, ['No', 'Unit Kerja']);

// Isi data
$no =$start + 1;
foreach ($dataPage as $item) {
    fputcsv($output, [$no++, $item]);
}

fclose($output);
exit;
?>
