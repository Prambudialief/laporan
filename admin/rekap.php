<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include '../template_admin/header.php';
include '../template_admin/navbar.php';
include '../template_admin/sidebar.php';
include '../services/connection.php';


// --- Ambil daftar aplikasi ---
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


function getFilter($key)
{
    return $_POST[$key] ?? $_GET[$key] ?? '';
}

$nama_aplikasi = getFilter('nama_aplikasi');
$tanggal_mulai   = getFilter('tanggal_mulai');
$tanggal_selesai = getFilter('tanggal_selesai');
$masalah = getFilter('masalah');

$limit = 10;
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$offset = ($page - 1) * $limit;

$query = "
    SELECT 
        nama_aplikasi,
        jenis_permasalahan,
        COUNT(*) AS total_masuk,
        SUM(CASE WHEN status_laporan = 'Selesai' THEN 1 ELSE 0 END) AS total_selesai,
        AVG(durasi) AS rata_durasi,
        nama_petugas
    FROM laporan
";

$filter = [];
$filter[] = "status_laporan = 'Selesai'";

if ($nama_aplikasi !== '') {
    $app = mysqli_real_escape_string($conn, $nama_aplikasi);
    $filter[] = "nama_aplikasi = '$app'";
}

if ($masalah !== '') {
    $mslh = mysqli_real_escape_string($conn, $masalah);
    $filter[] = "jenis_permasalahan = '$mslh'";
}

// Filter tanggal mulai 
$tanggal_mulai = $_POST['tanggal_mulai'] ?? $_GET['tanggal_mulai'] ?? '';
$tanggal_selesai = $_POST['tanggal_selesai'] ?? $_GET['tanggal_selesai'] ?? '';
if ($tanggal_mulai !== '' && $tanggal_selesai !== '') {
    $mulai = mysqli_real_escape_string($conn, $tanggal_mulai);
    $akhir = mysqli_real_escape_string($conn, $tanggal_selesai);
    $filter[] = "DATE(waktu_pelaporan) BETWEEN '$mulai' AND '$akhir'";
}

if (count($filter) > 0) {
    $query .= " WHERE " . implode(" AND ", $filter);
}

$query .= " GROUP BY nama_aplikasi, jenis_permasalahan, nama_petugas 
            ORDER BY nama_aplikasi ASC";
$query .= " LIMIT $limit OFFSET $offset";

// Hitung total data untuk pagination
$count_query = "
    SELECT COUNT(*) AS total
    FROM (
        SELECT nama_aplikasi FROM laporan
        " . (count($filter) > 0 ? " WHERE " . implode(" AND ", $filter) : "") . "
        GROUP BY nama_aplikasi, nama_petugas
    ) AS dummy
";

$count_result = mysqli_query($conn, $count_query);
$total_data = ($count_result) ? $count_result->fetch_assoc()['total'] : 0;
$total_pages = ceil($total_data / $limit);

// Eksekusi query utama
$result = mysqli_query($conn, $query);

?>

<style>
    .form-container {
        background: #fff;
        border-radius: 15px;
        padding: 25px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
    }
</style>

<div class="container-fluid mt-4">
    <h3 class="fw-bold mb-3 text-center">Rekapitulasi Laporan</h3>

    <div class="card shadow-sm">
        <div class="card-body form-container">

            <form action="" method="post" class="bg-light p-3 rounded">

                <div class="row g-3">

                    <div class="col-lg-4 col-md-4 col-sm-6">
                        <label class="form-label fw-semibold">Waktu Awal</label>
                        <input type="date" class="form-control" name="tanggal_mulai"
                            value="<?= $_POST['tanggal_mulai'] ?? $_GET['tanggal_mulai'] ?? '' ?>">

                        <label class="form-label fw-semibold">Waktu Akhir</label>
                        <input type="date" class="form-control" name="tanggal_selesai"
                            value="<?= $_POST['tanggal_selesai'] ?? $_GET['tanggal_selesai'] ?? '' ?>">
                    </div>

                    <div class="col-lg-4 col-md-6 col-sm-12">
                        <label class="form-label fw-semibold">Aplikasi</label>
                        <select name="nama_aplikasi" class="form-select">
                            <option value="">-- Pilih Aplikasi --</option>
                            <?php
                            foreach ($appList as $app) {
                                $sel = ($nama_aplikasi === $app) ? 'selected' : '';
                                echo "<option value='$app' $sel>$app</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-12">
                        <label class="form-label fw-semibold">Jenis Permasalahan</label>
                        <select name="masalah" class="form-select w-100">
                            <option value="">-- Pilih Permasalahan --</option>
                            <?php
                            foreach ($mslhList as $mslh) {
                                $selected = ($masalah === $mslh) ? 'selected' : '';
                                echo "<option value='$mslh' $selected>$mslh</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-lg-3 col-md-8 col-sm-12">

                        <div class="d-flex flex-wrap gap-2">
                            <button type="submit" class="btn btn-outline-primary flex-fill">Tampilkan</button>

                            <!-- Tombol PDF & Excel -->
                            <?php
                            $q = http_build_query([
                                'tanggal_mulai' => $tanggal_mulai,
                                'tanggal_selesai' => $tanggal_selesai,
                                'nama_aplikasi' => $nama_aplikasi,
                                'masalah' => $masalah
                            ]);
                            ?>

                            <button type="submit" formaction="cetakRekap.php?<?= $q ?>" formtarget="_blank" class="btn btn-outline-danger flex-fill">PDF</button>
                            <button type="submit" formaction="cetakRekapExcel.php?<?= $q ?>" formtarget="_blank" class="btn btn-outline-success flex-fill">Excel</button>
                        </div>
                    </div>
                </div>
            </form>


            <!-- ======================= TABLE ========================= -->
            <div class="mt-4 table-responsive">
                <table class="table table-bordered table-striped text-center align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>Nama Aplikasi</th>
                            <th>Jenis Permasalahan</th>
                            <th>Jumlah Masuk</th>
                            <th>Selesai</th>
                            <th>Rata Durasi</th>
                            <th>Petugas</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($result && mysqli_num_rows($result) > 0) {
                            while ($row = mysqli_fetch_assoc($result)) {
                                $durasiMenit = intval($row['rata_durasi']);
                                $jam = floor($durasiMenit / 60);
                                $menit = $durasiMenit % 60;
                                $durasiFormat = ($jam > 0)
                                    ? "$jam jam $menit menit"
                                    : "$menit menit";

                                echo "
                                <tr>
                                    <td class='text-start'>{$row['nama_aplikasi']}</td>
                                    <td>{$row['jenis_permasalahan']}</td>
                                    <td>{$row['total_masuk']}</td>
                                    <td>{$row['total_selesai']}</td>
                                    <td>{$durasiFormat}</td>
                                    <td>{$row['nama_petugas']}</td>
                                </tr>";
                            }
                        } else {
                            echo "<tr><td colspan='5'>Tidak ada data ditemukan.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>


            <!-- ======================= PAGINATION ========================= -->
            <nav>
                <ul class="pagination justify-content-center flex-wrap">

                    <?php
                    if ($total_pages > 1) {
                        for ($i = 1; $i <= $total_pages; $i++) {

                            $params = [
                                'page' => $i,
                                'tanggal_mulai' => $tanggal_mulai,
                                'tanggal_selesai' => $tanggal_selesai,
                                'nama_aplikasi' => $nama_aplikasi,
                                'masalah' => $masalah
                            ];

                            $queryString = http_build_query($params);
                            $active = ($i == $page) ? "active" : "";

                            echo "
                            <li class='page-item $active'>
                                <a class='page-link' href='?$queryString'>$i</a>
                            </li>";
                        }
                    }
                    ?>
                </ul>
            </nav>

        </div>
    </div>
</div>

<?php include '../template_admin/footer.php'; ?>
