<?php
$aplikasi = [
    "Aplikasi E-Procurement (LPSE)",
        "Aplikasi Simpeg",
        "Aplikasi (Simonev)+e-performance",
        "Aplikasi Integrated Maritime Surveillance",
        "Aplikasi Klinik",
        "Aplikasi SIPI",
        "Aplikasi Data Basarnas",
        "Aplikasi Balai Diklat Basarnas",
        "Aplikasi SSO",
        "Aplikasi (Rescue 115)",
        "Aplikasi Persuratan",
        "Aplikasi Arsip",
        "Aplikasi Bina Potensi",
        "Aplikasi Potensi Operasi",
        "Aplikasi Aset IT",
        "Aplikasi e-dupak",
        "Aplikasi GIS Land",
        "Aplikasi INASOC",
        "Aplikasi Manajemen Kerjasama Teknis",
        "Aplikasi PPNPN",
        "Aplikasi Kesiapsiagaan",
        "Aplikasi Aset Tanah",
        "Aplikasi Eksekutif",
        "Aplikasi SKM",
        "Aplikasi Dumas",
        "Aplikasi e-kinerja BKN",
        "Aplikasi Dharma Wanita",
        "Aplikasi Simpati",
        "Aplikasi Absensi Online",
        "Basarnas Drive",
        "Sistem Informasi Reformasi Birokrasi",
        "Aplikasi Digital Signature",
        "Aplikasi Data Tenaga",
        "Aplikasi Sikap",
        "Aplikasi Sigap",
        "Aplikasi Surat",
        "Sistem Informasi Saras",
        "Aplikasi Penilaian Kinerja Pegawai",
        "Website Data Services",
        "Website PPID"
];

$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$rowsPerPage = 10;

$start = ($page - 1) * $rowsPerPage;
$dataPage = array_slice($aplikasi, $start, $rowsPerPage);

header("Content-Type: text/csv");
header("Content-Disposition: attachment; filename=daftar_aplikasi_page_$page.csv");

$output = fopen("php://output", "w");

fputcsv($output, ['No', 'Nama Aplikasi']);

$no = $start + 1;
foreach ($dataPage as $item) {
    fputcsv($output, [$no++, $item]);
}

fclose($output);
exit;
?>
