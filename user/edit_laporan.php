<?php
date_default_timezone_set('Asia/Jakarta');
include '../template/header.php';
include '../template/navbar.php';
include '../template/sidebar.php';
include '../services/connection.php';

if (!isset($_GET['id'])) {
    echo "<script>alert('ID laporan tidak ditemukan!'); window.location='daftar_laporan.php';</script>";
    exit;
}

$id = intval($_GET['id']);
$q = $conn->query("SELECT * FROM laporan WHERE id = $id");
$data = $q->fetch_assoc();

if (!$data) {
    echo "<script>alert('Data tidak ditemukan!'); window.location='daftar_laporan.php';</script>";
    exit;
}

if (isset($_POST['update'])) {
    $judul_laporan = mysqli_real_escape_string($conn, $_POST['judul_laporan']);
    $nama_aplikasi = mysqli_real_escape_string($conn, $_POST['nama_aplikasi']);
    $deskripsi = mysqli_real_escape_string($conn, $_POST['deskripsi_permasalahan']);
    $solusi = mysqli_real_escape_string($conn, $_POST['solusi_permasalahan']);
    $jenis = mysqli_real_escape_string($conn, $_POST['jenis_permasalahan']);
    $status = mysqli_real_escape_string($conn, $_POST['status_laporan']);
    $lanjuti_id = !empty($_POST['lanjuti_id'])
    ? intval($_POST['lanjuti_id'])
    : NULL;


    // ---------- HANDLE TANGGAL SELESAI ----------
    $tanggal_input = !empty($_POST['tanggal_penyelesaian'])
        ? date("Y-m-d H:i:s", strtotime($_POST['tanggal_penyelesaian']))
        : NULL;

    if ($status === "Selesai") {

        // Jika tidak diisi manual → gunakan waktu sekarang
        if (!$tanggal_input) {
            $tanggal_selesai = date("Y-m-d H:i:s");
        } else {
            $tanggal_selesai = $tanggal_input;
        }

        // Hitung durasi FINAL dari waktu_pelaporan → tanggal_selesai
        $start = strtotime($data['waktu_pelaporan']);
        $end = strtotime($tanggal_selesai);

        if ($end < $start) {
            $end = $start; // cegah minus
        }

        $durasi = floor(($end - $start) / 60);
    } else {
        // Jika belum selesai → pakai durasi realtime dari hidden input
        $durasi = intval($_POST['durasi']);
        $tanggal_selesai = NULL;
    }

    // ---------- UPDATE DATABASE ----------
    $sql = "UPDATE laporan SET 
        judul_laporan='$judul_laporan',
        nama_aplikasi='$nama_aplikasi',
        deskripsi_permasalahan='$deskripsi',
        solusi_permasalahan='$solusi',
        jenis_permasalahan='$jenis',
        status_laporan='$status',
        lanjuti_id=" . ($lanjuti_id === NULL ? "NULL" : "'$lanjuti_id'") . ",
        tanggal_penyelesaian=" . ($tanggal_selesai ? "'$tanggal_selesai'" : "NULL") . ",
        durasi='$durasi'
        WHERE id='$id'";

    $conn->query($sql);

    echo "<script>alert('Laporan berhasil diperbarui!'); window.location='daftar_laporan.php';</script>";
    exit;
}

// LOAD MASTER DATA
$appList = $conn->query("SELECT nama_aplikasi FROM master_aplikasi ORDER BY nama_aplikasi ASC");
$jenisList = $conn->query("SELECT jenis_permasalahan FROM master_permasalahan ORDER BY jenis_permasalahan ASC");
$lanjutiList = $conn->query("
    SELECT id, nama_lanjuti 
    FROM master_lanjuti 
    ORDER BY nama_lanjuti ASC
");
?>

<div class="container mt-4 mb-3">
    <h3 class="fw-bold mb-3 text-center">Edit Laporan</h3>

    <form method="POST">

        <div class="mb-3">
            <label>Judul Laporan</label>
            <input type="text" name="judul_laporan" class="form-control" value="<?= htmlspecialchars($data['judul_laporan']) ?>" required>
        </div>

        <div class="mb-3">
            <label>Nama Aplikasi</label>
            <select name="nama_aplikasi" class="form-select" required>
                <option value="">-- Pilih Aplikasi --</option>
                <?php while ($r = $appList->fetch_assoc()): ?>
                    <option value="<?= htmlspecialchars($r['nama_aplikasi']) ?>"
                        <?= $data['nama_aplikasi'] == $r['nama_aplikasi'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($r['nama_aplikasi']) ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>

        <div class="mb-3">
            <label>Nama Lanjuti</label>
            <select name="lanjuti_id" class="form-select">
                <option value="">-- Pilih Lanjuti --</option>
                <?php while ($r = $lanjutiList->fetch_assoc()): ?>
                    <option
                        value="<?= $r['id'] ?>"
                        <?= ($data['lanjuti_id'] == $r['id']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($r['nama_lanjuti']) ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>

        <div class="mb-3">
            <label>Deskripsi Permasalahan</label>
            <textarea name="deskripsi_permasalahan" class="form-control" rows="3"><?= htmlspecialchars($data['deskripsi_permasalahan']) ?></textarea>
        </div>

        <div class="mb-3">
            <label>Jenis Permasalahan</label>
            <select name="jenis_permasalahan" class="form-select" required>
                <option value="">-- Pilih Permasalahan --</option>
                <?php while ($r = $jenisList->fetch_assoc()): ?>
                    <option
                        value="<?= htmlspecialchars($r['jenis_permasalahan']) ?>"
                        <?= $data['jenis_permasalahan'] == $r['jenis_permasalahan'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($r['jenis_permasalahan']) ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>

        <div class="mb-3">
            <label>Solusi Permasalahan</label>
            <textarea name="solusi_permasalahan" class="form-control" rows="3"><?= htmlspecialchars($data['solusi_permasalahan']) ?></textarea>
        </div>

        <div class="mb-3">
            <label>Status Laporan</label>
            <select name="status_laporan" class="form-select" id="status_laporan">
                <option value="Selesai" <?= $data['status_laporan'] == 'Selesai' ? 'selected' : '' ?>>Selesai</option>
                <option value="Proses" <?= $data['status_laporan'] == 'Proses' ? 'selected' : '' ?>>Proses</option>
                <option value="Pending" <?= $data['status_laporan'] == 'Pending' ? 'selected' : '' ?>>Pending</option>
            </select>
        </div>

        <?php
        $valueTanggalSelesai = '';
        if (!empty($data['tanggal_penyelesaian'])) {
            // format untuk datetime-local tanpa detik
            $valueTanggalSelesai = date('Y-m-d\TH:i', strtotime($data['tanggal_penyelesaian']));
        }
        ?>
        <div class="mb-3">
            <label>Tanggal Penyelesaian</label>
            <input
                type="datetime-local"
                class="form-control"
                name="tanggal_penyelesaian"
                id="tanggal_penyelesaian"
                value="<?= $valueTanggalSelesai ?>">
        </div>

        <?php
        $dm = (int)$data['durasi'];
        $h = floor($dm / 60);
        $m = $dm % 60;
        $durasiFormat = ($h > 0) ? "$h jam $m menit" : "$m menit";
        ?>

        <div class="mb-3">
            <label>Durasi</label>
            <p id="durasiRealtime" class="text-primary fw-bold"></p>
            <input type="text" value="<?= htmlspecialchars($durasiFormat) ?>" class="form-control mb-2" readonly>
            <input type="hidden" name="durasi" id="durasiHidden" value="<?= (int)$dm ?>">
        </div>

        <button type="submit" name="update" class="btn btn-primary w-100">Update</button>
    </form>
</div>

<script>
    // gunakan timestamp MS dari PHP agar konsisten (hindari parsing string)
    const waktuPelaporanMs = <?= (int)strtotime($data['waktu_pelaporan']) * 1000 ?>;
    const statusSelect = document.getElementById("status_laporan");
    const durasiRealtimeEl = document.getElementById("durasiRealtime");
    const durasiHidden = document.getElementById("durasiHidden");
    const tanggalInput = document.getElementById("tanggal_penyelesaian");

    function formatDurasiFromMinutes(totalMinutes) {
        const h = Math.floor(totalMinutes / 60);
        const m = totalMinutes % 60;
        if (totalMinutes <= 0) return "-";
        if (h > 0) return `${h} jam ${m} menit`;
        return `${m} menit`;
    }

    function hitungDurasi() {
        const status = statusSelect.value;

        if (status === "Selesai") {
            durasiRealtimeEl.innerText = "";
            return;
        }

        const nowMs = Date.now();
        let totalMinutes = Math.floor((nowMs - waktuPelaporanMs) / 60000);
        if (totalMinutes < 0) totalMinutes = 0;

        durasiRealtimeEl.innerText = "Durasi berjalan: " + formatDurasiFromMinutes(totalMinutes);
        durasiHidden.value = totalMinutes;
    }

    // generate local datetime string tanpa detik untuk datetime-local input
    function nowLocalDatetimeWithoutSeconds() {
        const now = new Date();
        const Y = now.getFullYear();
        const M = String(now.getMonth() + 1).padStart(2, '0');
        const D = String(now.getDate()).padStart(2, '0');
        const h = String(now.getHours()).padStart(2, '0');
        const m = String(now.getMinutes()).padStart(2, '0');
        return `${Y}-${M}-${D}T${h}:${m}`;
    }

    // setInterval untuk update durasi setiap menit (juga update segera)
    setInterval(hitungDurasi, 60000);
    hitungDurasi();

    // Jika status berubah ke selesai otomatis isi tanggal selesai (local time, tanpa detik)
    statusSelect.addEventListener("change", function() {
        if (this.value === "Selesai") {
            tanggalInput.value = nowLocalDatetimeWithoutSeconds();
            durasiRealtimeEl.innerText = "";
        } else {
            // jika balik ke Proses/Pending, kosongkan tanggal_penyelesaian (boleh disesuaikan)
            // tanggalInput.value = '';
            hitungDurasi();
        }
    });
</script>

<?php include '../template/footer.php'; ?>