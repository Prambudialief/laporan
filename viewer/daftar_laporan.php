<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'viewer') {
    header("Location: ../auth/login.php");
    exit;
}
include '../template_viewer/header.php';
include '../template_viewer/navbar.php';
include '../template_viewer/sidebar.php';
include '../services/connection.php';

// LOAD master aplikasi untuk filter
$appList = [];
$appQuery = $conn->query("SELECT nama_aplikasi FROM master_aplikasi ORDER BY nama_aplikasi ASC");
if ($appQuery && $appQuery->num_rows > 0) {
    while ($row = $appQuery->fetch_assoc()) {
        $appList[] = $row['nama_aplikasi'];
    }
}

$mslhList = [];
$mslhQuery = $conn->query("SELECT DISTINCT jenis_permasalahan FROM laporan ORDER BY jenis_permasalahan ASC");
if ($mslhQuery && $mslhQuery->num_rows > 0) {
    while ($row = $mslhQuery->fetch_assoc()) {
        $mslhList[] = $row['jenis_permasalahan'];
    }
}
?>

<style>
    .form-container {
        background: #fff;
        border-radius: 15px;
        padding: 30px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    }

    @media (max-width: 768px) {
        .form-container {
            padding: 20px;
        }
    }
</style>

<div class="container-fluid mt-4">
    <h3 class="fw-bold mb-3 text-center">Daftar Laporan</h3>

    <div class="card shadow-sm">
        <div class="card-body form-container">
            <form action="" method="POST" enctype="multipart/form-data" class="p-3 bg-light rounded">
                <div class="row g-3">
                    <div class="col-lg-3 col-md-4 col-sm-6">

                        <label class="form-label fw-semibold">Waktu Awal</label>
                        <input type="date" class="form-control" name="tanggal_mulai"
                            value="<?= $_POST['tanggal_mulai'] ?? $_GET['tanggal_mulai'] ?? '' ?>">

                        <label class="form-label fw-semibold">Waktu Akhir</label>
                        <input type="date" class="form-control" name="tanggal_selesai"
                            value="<?= $_POST['tanggal_selesai'] ?? $_GET['tanggal_selesai'] ?? '' ?>">
                    </div>

                    <div class="col-lg-3 col-md-8 col-sm-12">
                        <label class="form-label fw-semibold">Nama Aplikasi</label>
                        <select class="form-select" name="nama_aplikasi" style="max-height: 200px; overflow-y: auto;">
                            <option value="">-- Pilih Aplikasi --</option>
                            <?php
                            $selectedApp = $_POST['nama_aplikasi'] ?? $_GET['nama_aplikasi'] ?? '';
                            foreach ($appList as $app) {
                                $sel = ($selectedApp === $app) ? 'selected' : '';
                                echo "<option value='" . htmlspecialchars($app) . "' $sel>" . htmlspecialchars($app) . "</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <div class="col-lg-2 col-md-6 col-sm-12">
                        <label class="form-label fw-semibold">Jenis Permasalahan</label>
                        <select name="masalah" class="form-select w-100">
                            <option value="">-- Pilih Permasalahan --</option>
                            <?php
                            foreach ($mslhList as $mslh) {
                                $selected = ($masalah === $mslh) ? 'selected' : '';
                                echo "<option value='" . htmlspecialchars($mslh) . "' $selected>" . htmlspecialchars($mslh) . "</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <div class="col-lg-2 col-md-6 col-sm-12">
                        <label class="form-label fw-semibold">Status</label>
                        <select class="form-select" name="status">
                            <option value="">-- Pilih Status --</option>
                            <option value="Pending" <?= (($selectedStatus ?? '') === 'Pending') ? 'selected' : '' ?>>Pending</option>
                            <option value="Proses" <?= (($selectedStatus ?? '') === 'Proses') ? 'selected' : '' ?>>Proses</option>
                            <option value="Selesai" <?= (($selectedStatus ?? '') === 'Selesai') ? 'selected' : '' ?>>Selesai</option>
                        </select>
                    </div>

                    <div class="col-lg-2 col-md-6 col-sm-12">
                        <label class="form-label fw-semibold">Cari</label>
                        <input type="text" class="form-control" name="cari" placeholder="Cari data..." value="<?= $_POST['cari'] ?? $_GET['cari'] ?? '' ?>">
                    </div>

                    <div class="col-lg-3 col-md-8 col-sm-12">
                        <button type="submit" class="btn btn-outline-primary w-100 mt-1 mb-2">Tampilkan</button>

                        <button
                            type="submit"
                            formaction="cetakLaporan.php?tanggal_mulai=<?= $_POST['tanggal_mulai'] ?? '' ?>&tanggal_selesai=<?= $_POST['tanggal_selesai'] ?? '' ?>&q=<?= urlencode($_GET['q'] ?? '') ?>&nama_aplikasi=<?= urlencode($_POST['nama_aplikasi'] ?? '') ?>&masalah=<?= $_POST['masalah'] ?? '' ?>&status=<?= $_POST['status'] ?? '' ?>&cari=<?= $_POST['cari'] ?? '' ?>"
                            formtarget="_blank"
                            class="btn btn-outline-danger w-100 mt-1 mb-2">
                            PDF
                        </button>

                        <button
                            type="submit"
                            formaction="cetakExcel.php?tanggal_mulai=<?= $_POST['tanggal_mulai'] ?? '' ?>&tanggal_selesai=<?= $_POST['tanggal_selesai'] ?? '' ?>&q=<?= urlencode($_GET['q'] ?? '') ?>&nama_aplikasi=<?= urlencode($_POST['nama_aplikasi'] ?? '') ?>&masalah=<?= $_POST['masalah'] ?? '' ?>&status=<?= $_POST['status'] ?? '' ?>&cari=<?= $_POST['cari'] ?? '' ?>"
                            formtarget="_blank"
                            class="btn btn-outline-success w-100 mt-1 mb-2">
                            Excel
                        </button>
                    </div>
                </div>
            </form>

            <div class="mt-4">
                <?php
                $limit = 10;
                $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
                $offset = ($page - 1) * $limit;

                $query = "
                        SELECT 
                        l.*,
                        ml.nama_lanjuti
                        FROM laporan l
                        LEFT JOIN master_lanjuti ml 
                        ON l.lanjuti_id = ml.id
                    ";

                $count_query = "
                    SELECT COUNT(*) AS total
                    FROM laporan l
                    LEFT JOIN master_lanjuti ml 
                        ON l.lanjuti_id = ml.id
                ";
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

                $nama_aplikasi = $_POST['nama_aplikasi'] ?? $_GET['nama_aplikasi'] ?? '';
                if ($nama_aplikasi !== '') {
                    $nama_aplikasi = mysqli_real_escape_string($conn, $nama_aplikasi);
                    $filter[] = "nama_aplikasi = '$nama_aplikasi'";
                }

                $cari = $_POST['cari'] ?? $_GET['cari'] ?? '';
                if ($cari !== '') {
                    $cari = mysqli_real_escape_string($conn, $cari);
                    $filter[] = "(nama_pelapor LIKE '%$cari%' 
                    OR nama_petugas LIKE '%$cari%' 
                    OR deskripsi_permasalahan LIKE '%$cari%'
                    OR kantor_sar LIKE '%$cari%'
                    OR unit_kerja LIKE '%$cari%')";
                }

                $status = $_POST['status'] ?? $_GET['status'] ?? '';
                if ($status !== '') {
                    $status = mysqli_real_escape_string($conn, $status);
                    $filter[] = "status_laporan = '$status'";
                }

                $tanggal_mulai = $_POST['tanggal_mulai'] ?? $_GET['tanggal_mulai'] ?? '';
                $tanggal_selesai = $_POST['tanggal_selesai'] ?? $_GET['tanggal_selesai'] ?? '';
                if ($tanggal_mulai !== '' && $tanggal_selesai !== '') {
                    $mulai = mysqli_real_escape_string($conn, $tanggal_mulai);
                    $akhir = mysqli_real_escape_string($conn, $tanggal_selesai);
                    $filter[] = "DATE(waktu_pelaporan) BETWEEN '$mulai' AND '$akhir'";
                }

                $masalah = $_POST['masalah'] ?? $_GET['masalah'] ?? '';
                if ($masalah !== '') {
                    $masalah = mysqli_real_escape_string($conn, $masalah);
                    $filter[] = "jenis_permasalahan = '$masalah'";
                }


                if (count($filter) > 0) {
                    $where = " WHERE " . implode(" AND ", $filter);
                    $query .= $where;
                    $count_query .= $where;
                }

                $query .= " ORDER BY id DESC LIMIT $limit OFFSET $offset";

                $result = $conn->query($query);
                $count_result = $conn->query($count_query);
                $total_data = $count_result->fetch_assoc()['total'];
                $total_pages = ceil($total_data / $limit);
                ?>

                <div class="table-responsive">
                    <table class="table table-bordered table-striped align-middle text-center">
                        <thead class="table-dark">
                            <tr>
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
                                <th>File Permasalahan</th>
                                <th>Deskripsi Solusi</th>
                                <th>Jenis Permasalahan</th>
                                <th>Status</th>
                                <th>Durasi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if ($result && $result->num_rows > 0) {
                                $no = $offset + 1;
                                while ($row = $result->fetch_assoc()) {

                                    // ===== PERBAIKAN DURASI SESUAI PERMINTAAN =====
                                    $durasiMenit = (int)$row['durasi'];
                                    $status = $row['status_laporan'];

                                    if ($status === 'Pending' || $status === 'Proses') {
                                        // Jika belum selesai → tampilkan "-"
                                        $durasiFormat = "-";
                                    } else {
                                        // Jika selesai → gunakan durasi final dari DB
                                        $jam = floor($durasiMenit / 60);
                                        $menit = $durasiMenit % 60;

                                        if ($durasiMenit <= 0) {
                                            $durasiFormat = "-";
                                        } elseif ($jam > 0) {
                                            $durasiFormat = "$jam jam $menit menit";
                                        } else {
                                            $durasiFormat = "$menit menit";
                                        }
                                    }
                                    // ===== END PERBAIKAN =====

                                    echo "<tr>
                                        <td class='text-center'>{$no}</td>
                                        <td>{$row['waktu_pelaporan']}</td>
                                        <td>" . htmlspecialchars($row['tanggal_penyelesaian']) . "</td>
                                        <td>" . htmlspecialchars($row['nama_aplikasi']) . "</td>
                                        <td>" . htmlspecialchars($row['nama_pelapor']) . "</td>
                                        <td>" . htmlspecialchars($row['kantor_sar']) . "</td>
                                        <td>" . htmlspecialchars($row['unit_kerja']) . "</td>
                                        <td>" . htmlspecialchars($row['nama_petugas']) . "</td>
                                        <td>" . htmlspecialchars($row['nama_lanjuti']) . "</td>
                                        <td>" . htmlspecialchars($row['deskripsi_permasalahan']) . "</td>
                                        <td class='text-center'>" .
                                        (!empty($row['gambar_deskripsi'])
                                            ? "<a href='../upload/" . htmlspecialchars($row['gambar_deskripsi']) . "' target='_blank' class='btn btn-sm btn-info'>Lihat File</a>"
                                            : "-") .
                                        "</td>
                                        <td>" . htmlspecialchars($row['solusi_permasalahan']) . "</td>
                                        <td>" . htmlspecialchars($row['jenis_permasalahan']) . "</td>
                                        <td>" . htmlspecialchars($row['status_laporan']) . "</td>
                                        <td>$durasiFormat</td>";
                                    $no++;
                                }
                            } else {
                                echo "<tr><td colspan='15' class='text-center'>Belum ada laporan.</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <nav>
                    <ul class="pagination justify-content-center flex-wrap">
                        <?php
                        if ($total_pages > 1) {
                            for ($i = 1; $i <= $total_pages; $i++) {
                                $params = [
                                    'page' => $i,
                                    'tanggal_mulai' => $_POST['tanggal_mulai'] ?? '',
                                    'tanggal_selesai' => $_POST['tanggal_selesai'] ?? '',
                                    'nama_aplikasi' => $_POST['nama_aplikasi'] ?? '',
                                    'masalah' => $_POST['masalah'] ?? '',
                                    'status' => $_POST['status'] ?? '',
                                    'cari' => $_POST['cari'] ?? ''
                                ];
                                $queryString = http_build_query($params);
                                $active = $i == $page ? 'active' : '';
                                echo "<li class='page-item $active'><a class='page-link' href='?{$queryString}'>$i</a></li>";
                            }
                        }
                        ?>
                    </ul>
                </nav>

            </div>
        </div>
    </div>
</div>

<?php include '../template_viewer/footer.php'; ?>