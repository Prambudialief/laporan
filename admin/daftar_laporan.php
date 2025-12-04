<?php
session_start();
require_once '../template_admin/header.php';
require_once '../template_admin/navbar.php';
require_once '../template_admin/sidebar.php';
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
    <h3 class="fw-bold mb-3 text-center text-md-start">Daftar Laporan</h3>

    <div class="card shadow-sm">
        <div class="card-body form-container">
            <form action="" method="POST" enctype="multipart/form-data" class="p-3 bg-light rounded">
                <div class="row g-3">

                    <div class="col-lg-3 col-md-4 col-sm-6">
                        <label class="form-label fw-semibold">Bulan Awal</label>
                        <input type="month" class="form-control" name="bulan_mulai" value="<?= $_POST['bulan_mulai'] ?? '' ?>">
                        <label class="form-label fw-semibold">Bulan Akhir</label>
                        <input type="month" class="form-control" name="bulan_selesai" value="<?= $_POST['bulan_selesai'] ?? '' ?>">
                        <button type="submit" class="btn btn-outline-primary w-100 mt-1 mb-2">Tampilkan</button>
                        <button
                            type="submit"
                            formaction="cetakLaporan.php?bulan_mulai=<?= $_POST['bulan_mulai'] ?? '' ?>&bulan_selesai=<?= $_POST['bulan_selesai'] ?? '' ?>&nama_aplikasi=<?= $_POST['nama_aplikasi'] ?? '' ?>&cari=<?= $_POST['cari'] ?? '' ?>"
                            formtarget="_blank"
                            class="btn btn-outline-danger w-100 mt-1 mb-2">
                            PDF
                        </button>

                        <button
                            type="submit"
                            formaction="cetakExcel.php?bulan_mulai=<?= $_POST['bulan_mulai'] ?? '' ?>&bulan_selesai=<?= $_POST['bulan_selesai'] ?? '' ?>&nama_aplikasi=<?= $_POST['nama_aplikasi'] ?? '' ?>&cari=<?= $_POST['cari'] ?? '' ?>"
                            formtarget="_blank"
                            class="btn btn-outline-success w-100 mt-1 mb-2">
                            Excel
                        </button>
                    </div>

                    <div class="col-lg-5 col-md-8 col-sm-12">
                        <label class="form-label fw-semibold">Nama Aplikasi</label>
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

                    <!-- Kolom kanan: pencarian -->
                    <div class="col-lg-4 col-md-6 col-sm-12">
                        <label class="form-label fw-semibold">Cari</label>
                        <input type="text" class="form-control" name="cari" placeholder="Cari data...">
                    </div>
                </div>
            </form>

            <div class="mt-4">
                <h4 class="mb-3 text-center text-md-start">Daftar Laporan</h4>

                <?php
                include "../services/connection.php";

                $limit = 10;
                $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
                $offset = ($page - 1) * $limit;

                $query = "SELECT * FROM laporan";
                $count_query = "SELECT COUNT(*) AS total FROM laporan";

                $filter = [];

                if (!empty($_POST['nama_aplikasi'])) {
                    $nama_aplikasi = mysqli_real_escape_string($conn, $_POST['nama_aplikasi']);
                    $filter[] = "nama_aplikasi = '$nama_aplikasi'";
                }

                if (!empty($_POST['cari'])) {
                    $cari = mysqli_real_escape_string($conn, $_POST['cari']);
                    $filter[] = "(nama_pelapor LIKE '%$cari%' OR nama_petugas LIKE '%$cari%' OR deskripsi_permasalahan LIKE '%$cari%')";
                }

                if (!empty($_POST['bulan_mulai']) && !empty($_POST['bulan_selesai'])) {

                    $mulai = $_POST['bulan_mulai'] . "-01"; 
                    $akhir = date("Y-m-t", strtotime($_POST['bulan_selesai'] . "-01")); 
                
                    $mulai = mysqli_real_escape_string($conn, $mulai);
                    $akhir = mysqli_real_escape_string($conn, $akhir);
                
                    $filter[] = "DATE(waktu_pelaporan) BETWEEN '$mulai' AND '$akhir'";
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
                                <th>ID</th>
                                <th>Tanggal Pelaporan</th>
                                <th>Nama Aplikasi</th>
                                <th>Nama Pelapor</th>
                                <th>Kantor SAR</th>
                                <th>Nama Petugas</th>
                                <th>Deskripsi Permasalahan</th>
                                <th>Jenis Permasalahan</th>
                                <th>Status</th>
                                <th>Durasi</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if ($result && $result->num_rows > 0) {
                                $no = 1;
                                while ($row = $result->fetch_assoc()) {
                                    $isSelesai = ($row['status_laporan'] === 'Selesai');
                                    $durasiMenit = (int)$row['durasi'];
                                    $jam = floor($durasiMenit / 60);
                                    $menit = $durasiMenit % 60;
                                    if ($jam > 0) {
                                        $durasiFormat = $jam . " jam " . $menit . " menit";
                                    } else {
                                        $durasiFormat = $menit . " menit";
                                    }
                                    echo "<tr>
                                    <td>{$no}</td> 
                                    <td>{$row['waktu_pelaporan']}</td>
                                    <td>{$row['nama_aplikasi']}</td>
                                    <td>{$row['nama_pelapor']}</td>
                                    <td>{$row['kantor_sar']}</td>
                                    <td>{$row['nama_petugas']}</td>
                                    <td class='text-center'>{$row['deskripsi_permasalahan']}</td>
                                    <td>{$row['jenis_permasalahan']}</td>
                                    <td>{$row['status_laporan']}</td>
                                    <td>{$durasiFormat}</td>
                                     <td>";

                                    // Jika selesai → tombol edit disabled
                                    if ($isSelesai) {
                                        echo "<button class='btn btn-sm btn-secondary mb-1 w-100 w-md-auto' disabled>Edit</button>";
                                    } else {
                                        echo "<a href='edit_laporan.php?id={$row['id']}' class='btn btn-sm btn-warning mb-1 w-100 w-md-auto'>Edit</a>";
                                    }

                                    echo "
                                    <a href='hapus_laporan.php?id={$row['id']}' class='btn btn-sm btn-danger w-100 w-md-auto' onclick='return confirm(\"Yakin ingin menghapus laporan ini?\")'>Hapus</a>
                                    </td>
                                    </tr>";
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
                                $active = $i == $page ? 'active' : '';
                                echo "<li class='page-item $active'><a class='page-link' href='?page=$i'>$i</a></li>";
                            }
                        }
                        ?>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
</div>

<?php
require_once '../template_admin/footer.php';
?>