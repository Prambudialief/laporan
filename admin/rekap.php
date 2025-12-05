<?php
session_start();
require_once '../template_admin/header.php';
require_once '../template_admin/navbar.php';
require_once '../template_admin/sidebar.php';
require_once '../services/connection.php';

$query = "SELECT nama_aplikasi, 
                 COUNT(*) AS total_masuk,
                 SUM(CASE WHEN status_laporan = 'Selesai' THEN 1 ELSE 0 END) AS total_selesai,
                 ROUND(AVG(durasi), 2) AS rata_durasi,
                 nama_petugas
          FROM laporan";

$filter = [];

if (!empty($_POST['judul_laporan'])) {
    $filter[] = "DATE(waktu_pelaporan) = '" . mysqli_real_escape_string($conn, $_POST['judul_laporan']) . "'";
}
if (!empty($_POST['tahun'])) {
    $filter[] = "YEAR(waktu_pelaporan) = '" . mysqli_real_escape_string($conn, $_POST['tahun']) . "'";
}
if (!empty($_POST['status_laporan'])) {
    $filter[] = "status_laporan = '" . mysqli_real_escape_string($conn, $_POST['status_laporan']) . "'";
}
if (!empty($_POST['nama_aplikasi'])) {
    $filter[] = "nama_aplikasi = '" . mysqli_real_escape_string($conn, $_POST['nama_aplikasi']) . "'";
}
if (!empty($_POST['petugas'])) {
    $filter[] = "nama_petugas LIKE '%" . mysqli_real_escape_string($conn, $_POST['petugas']) . "%'";
}

$bulan_mulai = $_POST['bulan_mulai'] ?? '';
$bulan_selesai = $_POST['bulan_selesai'] ?? '';

if ($bulan_mulai !== '' || $bulan_selesai !== '') {

    if ($bulan_mulai !== '' && $bulan_selesai === '') {
        $bulan_selesai = $bulan_mulai;
    }

    if ($bulan_mulai === '' && $bulan_selesai !== '') {
        $bulan_mulai = $bulan_selesai;
    }

    $mulai = $bulan_mulai . "-01";
    $akhir = date("Y-m-t", strtotime($bulan_selesai . "-01"));

    $mulai = mysqli_real_escape_string($conn, $mulai);
    $akhir = mysqli_real_escape_string($conn, $akhir);

    if ($mulai > $akhir) {
        $temp = $mulai;
        $mulai = $akhir;
        $akhir = $temp;
    }

    $filter[] = "DATE(waktu_pelaporan) BETWEEN '$mulai' AND '$akhir'";
}

if (count($filter) > 0) {
    $query .= " WHERE " . implode(" AND ", $filter);
}


$query .= " GROUP BY nama_aplikasi, nama_petugas ORDER BY nama_aplikasi ASC";

$result = mysqli_query($conn, $query);
?>

<style>
    .form-container {
        background: #fff;
        border-radius: 15px;
        padding: 25px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
    }

    @media (max-width: 768px) {
        .form-container {
            padding: 20px;
        }
    }
</style>

<div class="container-fluid mt-4">
    <h3 class="fw-bold mb-3 text-center text-md-start">Rekapitulasi Laporan</h3>

    <div class="card shadow-sm">
        <div class="card-body form-container">
            <form action="" method="post" enctype="multipart/form-data" class="bg-light p-3 rounded">
                <div class="row g-3">
                    <div class="col-lg-3 col-md-4 col-sm-6">
                        <label class="form-label fw-semibold">Bulan Awal</label>
                        <input type="month" class="form-control" name="bulan_mulai" value="<?= $_POST['bulan_mulai'] ?? '' ?>">
                        <label class="form-label fw-semibold">Bulan Akhir</label>
                        <input type="month" class="form-control" name="bulan_selesai" value="<?= $_POST['bulan_selesai'] ?? '' ?>">
                    </div>
                    <!-- Kolom tanggal -->
                    <div class="col-lg-3 col-md-4 col-sm-6">
                        <label class="form-label fw-semibold">Tanggal</label>
                        <input type="date" class="form-control" name="judul_laporan" value="<?= $_POST['judul_laporan'] ?? '' ?>">
                    </div>

                    <!-- Kolom tahun -->
                    <div class="col-lg-3 col-md-4 col-sm-6">
                        <label class="form-label fw-semibold">Tahun</label>
                        <input type="number" class="form-control" name="tahun" placeholder="2025" value="<?= $_POST['tahun'] ?? '' ?>">
                    </div>

                    <!-- Kolom status -->
                    <div class="col-lg-3 col-md-4 col-sm-6">
                        <label class="form-label fw-semibold">Status</label>
                        <select class="form-select" name="status_laporan">
                            <option value="">-- Pilih Status --</option>
                            <option value="Selesai" <?= (($_POST['status_laporan'] ?? '') == 'Selesai') ? 'selected' : '' ?>>Selesai</option>
                            <option value="Proses" <?= (($_POST['status_laporan'] ?? '') == 'Proses') ? 'selected' : '' ?>>Proses</option>
                            <option value="Pending" <?= (($_POST['status_laporan'] ?? '') == 'Pending') ? 'selected' : '' ?>>Pending</option>
                        </select>
                    </div>

                    <!-- Kolom aplikasi -->
                    <div class="col-lg-3 col-md-6 col-sm-12">
                        <label class="form-label fw-semibold">Aplikasi</label>
                        <select class="form-select" name="nama_aplikasi" size="5" style="max-height: 200px; overflow-y: auto;">
                            <option value="">-- Pilih Aplikasi --</option>
                            <option value="Aplikasi E-Procurement">Aplikasi E-Procurement (LPSE)</option>
                            <option value="Aplikasi Simpeg">Aplikasi Simpeg</option>
                            <option value="Aplikasi Simonev+e-performance">Aplikasi (Simonev)+e-performance</option>
                            <option value="Aplikasi Integrated Maritime Surveillance">Aplikasi Integrated Maritime Surveillance</option>
                            <option value="Aplikasi Klinik">Aplikasi Klinik</option>
                            <option value="Aplikasi SIPI">Aplikasi SIPI</option>
                            <option value="Aplikasi Data Basarnas">Aplikasi Data Basarnas</option>
                            <option value="Aplikasi Balai Diklat Basarnas">Aplikasi Balai Diklat Basarnas</option>
                            <option value="Aplikasi SSO">Aplikasi SSO</option>
                            <option value="Aplikasi Rescue 115">Aplikasi (Rescue 115)</option>
                            <option value="Aplikasi Persuratan">Aplikasi Persuratan</option>
                            <option value="Aplikasi Arsip">Aplikasi Arsip</option>
                            <option value="Aplikasi Bina Potensi">Aplikasi Bina Potensi</option>
                            <option value="Aplikasi Potensi Operasi">Aplikasi Potensi Operasi</option>
                            <option value="Aplikasi Aset IT">Aplikasi Aset IT</option>
                            <option value="Aplikasi e-dupak">Aplikasi e-dupak</option>
                            <option value="Aplikasi GIS Land">Aplikasi GIS Land</option>
                            <option value="Aplikasi INASOC">Aplikasi INASOC</option>
                            <option value="Aplikasi Manajemen Kerjasama Teknis">Aplikasi Manajemen Kerjasama Teknis</option>
                            <option value="Aplikasi PPNPN">Aplikasi PPNPN</option>
                            <option value="Aplikasi Kesiapsiagaan">Aplikasi Kesiapsiagaan</option>
                            <option value="Aplikasi Aset Tanah">Aplikasi Aset Tanah</option>
                            <option value="Aplikasi Eksekutif">Aplikasi Eksekutif</option>
                            <option value="Aplikasi SKM">Aplikasi SKM</option>
                            <option value="Aplikasi Dumas">Aplikasi Dumas</option>
                            <option value="Aplikasi e-kinerja BKN">Aplikasi e-kinerja BKN</option>
                            <option value="Aplikasi Dharma Wanita">Aplikasi Dharma Wanita</option>
                            <option value="Aplikasi Simpati">Aplikasi Simpati</option>
                            <option value="Aplikasi Absensi Online">Aplikasi Absensi Online</option>
                            <option value="Basarnas Drive">Basarnas Drive</option>
                            <option value="Sistem Informasi Reformasi Birokrasi">Sistem Informasi Reformasi Birokrasi</option>
                            <option value="Aplikasi Digital Signature">Aplikasi Digital Signature</option>
                            <option value="Aplikasi Data Tenaga">Aplikasi Data Tenaga</option>
                            <option value="Aplikasi Sikap">Aplikasi Sikap</option>
                            <option value="Aplikasi Sigap">Aplikasi Sigap</option>
                            <option value="Aplikasi Surat">Aplikasi Surat</option>
                            <option value="Sistem Informasi Saras">Sistem Informasi Saras</option>
                            <option value="Aplikasi Penilaian Kinerja Pegawai">Aplikasi Penilaian Kinerja Pegawai</option>
                            <option value="Website Data Services">Website Data Services</option>
                            <option value="Website PPID">Website PPID</option>
                        </select>
                    </div>

                    <!-- Kolom petugas + tombol -->
                    <div class="col-lg-6 col-md-8 col-sm-12">
                        <label class="form-label fw-semibold">Petugas</label>
                        <input type="text" class="form-control mb-2" name="petugas" value="<?= $_POST['petugas'] ?? '' ?>" placeholder="Nama petugas...">

                        <div class="d-flex flex-wrap gap-2">
                            <button type="submit" class="btn btn-outline-primary flex-fill flex-md-grow-0">Tampilkan</button>
                            <button
                                type="submit"
                                formaction="cetakRekap.php?
                                judul_laporan=<?= $_POST['judul_laporan'] ?? '' ?>&bulan_mulai=<?= $_POST['bulan_mulai'] ?? '' ?>&bulan_selesai=<?= $_POST['bulan_selesai'] ?? '' ?>&
                                tahun=<?= $_POST['tahun'] ?? '' ?>&
                                status_laporan=<?= $_POST['status_laporan'] ?? '' ?>&
                                nama_aplikasi=<?= $_POST['nama_aplikasi'] ?? '' ?>&
                                petugas=<?= $_POST['petugas'] ?? '' ?>"
                                formtarget="_blank"
                                class="btn btn-outline-danger flex-fill flex-md-grow-0">
                                PDF
                            </button>

                            <button
                                type="submit"
                                formaction="cetakRekapExcel.php?
                                judul_laporan=<?= $_POST['judul_laporan'] ?? '' ?>&bulan_mulai=<?= $_POST['bulan_mulai'] ?? '' ?>&bulan_selesai=<?= $_POST['bulan_selesai'] ?? '' ?>&
                                tahun=<?= $_POST['tahun'] ?? '' ?>&
                                status_laporan=<?= $_POST['status_laporan'] ?? '' ?>&
                                nama_aplikasi=<?= $_POST['nama_aplikasi'] ?? '' ?>&
                                petugas=<?= $_POST['petugas'] ?? '' ?>"
                                formtarget="_blank"
                                class="btn btn-outline-success flex-fill flex-md-grow-0">
                                Excel
                            </button>
                        </div>
                    </div>
                </div>
            </form>

            <!-- Tabel responsif -->
            <div class="mt-4 table-responsive">
                <table class="table table-bordered table-striped align-middle text-center">
                    <thead class="table-dark">
                        <tr>
                            <th>Nama Aplikasi</th>
                            <th>Jumlah Laporan Masuk</th>
                            <th>Laporan Selesai</th>
                            <th>Rata-rata Durasi (Hari)</th>
                            <th>Petugas</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (mysqli_num_rows($result) > 0) {
                            while ($row = mysqli_fetch_assoc($result)) {
                                $durasiMenit = (int)$row['rata_durasi'];
                                    $jam = floor($durasiMenit / 60);
                                    $menit = $durasiMenit % 60;
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
                        } else {
                            echo "<tr><td colspan='5' class='text-center'>Tidak ada data ditemukan.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>


<?php
require_once '../template_admin/footer.php'; 
?>