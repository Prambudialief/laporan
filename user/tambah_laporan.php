<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}
include '../template/header.php';
include '../template/navbar.php';
include '../template/sidebar.php';
include 'sistemLaporan.php';

$aplikasi = mysqli_query($conn, "SELECT * FROM master_aplikasi ORDER BY nama_aplikasi ASC");
$permasalahan = mysqli_query($conn, "SELECT * FROM master_permasalahan ORDER BY jenis_permasalahan ASC");
$kantor = mysqli_query($conn, "SELECT * FROM master_kantor_sar ORDER BY kantor_sar ASC");
$unit = mysqli_query($conn, "SELECT * FROM master_unit_kerja ORDER BY unit_kerja ASC");
$pelaporan = mysqli_query($conn, "SELECT * FROM master_pelaporan ORDER BY pelaporan ASC");
$lanjuti = mysqli_query($conn, "SELECT * FROM master_lanjuti ORDER BY nama_lanjuti ASC");
?>

<style>
    .form-container {
        background: #fff;
        border-radius: 15px;
        padding: 30px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    }

    .form-label {
        font-weight: 500;
    }

    .btn-submit {
        background-color: #0d6efd;
        color: white;
        border-radius: 8px;
        padding: 10px 25px;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .btn-submit:hover {
        background-color: #084298;
    }
</style>

<div class="container-fluid mt-4">
    <h3 class="fw-bold text-center">Tambah Laporan</h3>
    <div class="container-fluid p-4 mt-4">
        <div class="card">
            <div class="card-body form-container">
                <form method="post" enctype="multipart/form-data" action="sistemLaporan.php">
                    <div class="row g-4">
                        <div class="col-12 col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Tambah Laporan<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="judul_laporan" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label text-muted">Nama Petugas</label>
                                <input type="text" class="form-control text-muted" name="nama_petugas"
                                    value="<?php echo htmlspecialchars($_SESSION['nama']); ?>" readonly>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Nama Aplikasi<span class="text-danger">*</span></label>
                                <select class="form-select w-100" name="nama_aplikasi" required>
                                    <option value="">-- Pilih Aplikasi --</option>
                                    <?php while ($row = mysqli_fetch_assoc($aplikasi)) : ?>
                                        <option value="<?= $row['nama_aplikasi'] ?>"><?= $row['nama_aplikasi'] ?></option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Nama Pelapor<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="nama_pelapor" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Jenis Permasalahan<span class="text-danger">*</span></label>
                                <select class="form-select w-100" name="jenis_permasalahan" required>
                                    <option value="">-- Pilih Permasalahan --</option>
                                    <?php while ($row = mysqli_fetch_assoc($permasalahan)) : ?>
                                        <option value="<?= $row['jenis_permasalahan'] ?>"><?= $row['jenis_permasalahan'] ?></option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Pilih Jenis Tujuan</label>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="checkSatker">
                                    <label class="form-check-label" for="checkSatker">
                                        Satker
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="checkUnit">
                                    <label class="form-check-label" for="checkUnit">
                                        Unit Kerja
                                    </label>
                                </div>
                            </div>

                            <div class="mb-3" id="wrap-satker">
                                <label class="form-label">Satker<span class="text-danger">*</span></label>
                                <select class="form-select w-100" name="kantor_sar" id="kantor_sar">
                                    <option value="">-- Pilih Satker --</option>
                                    <?php while ($row = mysqli_fetch_assoc($kantor)) : ?>
                                        <option value="<?= $row['kantor_sar'] ?>"><?= $row['kantor_sar'] ?></option>
                                    <?php endwhile; ?>
                                </select>
                            </div>

                            <div class="mb-3" id="wrap-unit">
                                <label class="form-label">Unit Kerja<span class="text-danger">*</span></label>
                                <select class="form-select w-100" name="unit_kerja">
                                    <option value="">-- Pilih Unit Kerja --</option>
                                    <?php while ($row = mysqli_fetch_assoc($unit)) : ?>
                                        <option value="<?= $row['unit_kerja'] ?>"><?= $row['unit_kerja'] ?></option>
                                    <?php endwhile; ?>
                                </select>

                            </div>

                            <div class="mb-3">
                                <label class="form-label">Media Pelaporan<span class="text-danger">*</span></label>
                                <select class="form-select" name="media_pelaporan" required>
                                    <option value="">-- Pilih Media --</option>
                                    <?php while ($row = mysqli_fetch_assoc($pelaporan)) : ?>
                                        <option value="<?= $row['pelaporan'] ?>"><?= $row['pelaporan'] ?></option>
                                    <?php endwhile; ?>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Waktu Pelaporan<span class="text-danger">*</span></label>
                                <input type="datetime-local" class="form-control" name="waktu_pelaporan" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Waktu Pemutakhiran<span class="text-danger">*</span></label>
                                <input type="datetime-local" class="form-control" name="tanggal_pemutakhiran" required>
                            </div>
                        </div>

                        <!-- Kolom Kanan -->
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Deskripsi Permasalahan<span class="text-danger">*</span></label>
                                <textarea class="form-control" name="deskripsi_permasalahan" rows="3" required></textarea>
                                <input class="form-control mt-3" type="file" name="gambar_deskripsi" id="gambar_deskripsi">
                                <p class="text-danger">Maksimal Ukuran File 2MB <span class="fw-bold">.all file</span></p>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Solusi Permasalahan</label>
                                <textarea class="form-control" name="solusi_permasalahan" rows="3"></textarea>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Tindak Lanjuti</label>
                                <select class="form-select" name="lanjuti_id" required>
                                    <option value="">-- Pilih --</option>
                                    <?php while ($row = mysqli_fetch_assoc($lanjuti)) : ?>
                                        <option value="<?= $row['id'] ?>">
                                            <?= $row['nama_lanjuti'] ?>
                                        </option>
                                    <?php endwhile; ?>
                                </select>

                            </div>
                            <div class="mb-3">
                                <label class="form-label">Status Laporan<span class="text-danger">*</span></label>
                                <select class="form-select" name="status_laporan" required>
                                    <option value="">-- Pilih Status --</option>
                                    <option value="Proses">Proses</option>
                                    <option value="Pending">Pending</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="text-end mt-1">
                        <button type="submit" name="simpan" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i> Simpan Laporan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    document.getElementById('gambar_deskripsi').addEventListener('change', function() {
        const file = this.files[0];
        if (file && file.size > 2 * 1024 * 1024) { // 2 MB
            alert("Ukuran file maksimal 2 MB!");
            this.value = ""; // reset file input
        }
    });
</script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const checkSatker = document.getElementById('checkSatker');
    const checkUnit = document.getElementById('checkUnit');

    const wrapSatker = document.getElementById('wrap-satker');
    const wrapUnit = document.getElementById('wrap-unit');

    const satker = document.getElementById('kantor_sar');
    const unit = document.getElementById('unit_kerja');

    function reset() {
        wrapSatker.style.display = "block";
        wrapUnit.style.display = "block";
        satker.value = "";
        unit.value = "";
        satker.removeAttribute("required");
        unit.removeAttribute("required");
    }

    checkSatker.addEventListener('change', function () {
        if (this.checked) {
            checkUnit.checked = false;
            wrapSatker.style.display = "block";
            wrapUnit.style.display = "none";
            satker.setAttribute("required", "required");
            unit.removeAttribute("required");
            unit.value = "";
        } else {
            reset();
        }
    });

    checkUnit.addEventListener('change', function () {
        if (this.checked) {
            checkSatker.checked = false;
            wrapUnit.style.display = "block";
            wrapSatker.style.display = "none";
            unit.setAttribute("required", "required");
            satker.removeAttribute("required");
            satker.value = "";
        } else {
            reset();
        }
    });
});
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const waktuPelaporan = document.querySelector('input[name="waktu_pelaporan"]');
        const tanggalPenyelesaian = document.querySelector('input[name="tanggal_penyelesaian"]');
        const durasiTampil = document.getElementById('durasi_tampil');
        const durasiHidden = document.getElementById('durasi');

        function hitungDurasi() {
            const start = new Date(waktuPelaporan.value);
            const end = new Date(tanggalPenyelesaian.value);

            if (waktuPelaporan.value && tanggalPenyelesaian.value && end > start) {
                const diffMs = end - start; // selisih dalam milidetik
                const diffMinutes = Math.floor(diffMs / 1000 / 60); // ke menit
                const hours = Math.floor(diffMinutes / 60);
                const minutes = diffMinutes % 60;

                let display = "";
                if (hours > 0) display += hours + " jam ";
                display += minutes + " menit";

                durasiTampil.value = display.trim();
                durasiHidden.value = diffMinutes; // simpan ke hidden input
            } else {
                durasiTampil.value = "";
                durasiHidden.value = "";
            }
        }

        waktuPelaporan.addEventListener('change', hitungDurasi);
        tanggalPenyelesaian.addEventListener('change', hitungDurasi);
    });
</script>


<?php
include '../template/footer.php';
?>