<?php
include '../template/header.php';
include '../template/navbar.php';
include '../template/sidebar.php';
if (!isset($_GET['id'])) {
    echo "<script>alert('ID laporan tidak ditemukan!'); window.location='daftar_laporan.php';</script>";
    exit;
}

$id = $_GET['id'];
$query = $conn->query("SELECT * FROM laporan WHERE id = '$id'");
$data = $query->fetch_assoc();

if (isset($_POST['update'])) {
    $judul_laporan = $_POST['judul_laporan'];
    $nama_aplikasi = $_POST['nama_aplikasi'];
    $status_laporan = $_POST['status_laporan'];
    $durasi = $_POST['durasi'];
    $deskripsi_permasalahan = $_POST['deskripsi_permasalahan'];
    $solusi_permasalahan = $_POST['solusi_permasalahan'];
    $jenis_permasalahan = $_POST['jenis_permasalahan'];

    $conn->query("UPDATE laporan SET 
        judul_laporan='$judul_laporan',
        nama_aplikasi='$nama_aplikasi',
        deskripsi_permasalahan='$deskripsi_permasalahan',
        solusi_permasalahan='$solusi_permasalahan',
        jenis_permasalahan='$jenis_permasalahan',
        status_laporan='$status_laporan',
        durasi='$durasi'
        WHERE id='$id'");

    echo "<script>alert('Data berhasil diperbarui!'); window.location='daftar_laporan.php';</script>";
}
?>
    <div class="container mt-4 mb-3">
        <h4>Edit Laporan</h4>
        <form method="POST">
            <div class="mb-3">
                <label>Judul Laporan</label>
                <input type="text" name="judul_laporan" value="<?= $data['judul_laporan'] ?>" class="form-control" required>
            </div>

            <div class="mb-3">
                <label>Nama Aplikasi</label>
                <input type="text" name="nama_aplikasi" value="<?= $data['nama_aplikasi'] ?>" class="form-control" required>
            </div>

            <div class="mb-3">
                <label>Deskripsi Permasalahan</label>
                <textarea name="deskripsi_permasalahan" class="form-control" rows="3"><?= $data['deskripsi_permasalahan'] ?></textarea>
            </div>

            <div class="mb-3">
                <label >Jenis Permasalahan</label>
                <select name="jenis_permasalahan" class="form-select">
                    <option value="Hacking" <?= $data['jenis_permasalahan'] == 'Hacking' ? 'selected' : ''?>>Hacking</option>
                    <option value="Down Time" <?= $data['jenis_permasalahan'] == 'Down Time' ? 'selected' : ''?>>Down Time</option>
                    <option value="Aplikasi" <?= $data['jenis_permasalahan'] == 'Aplikasi' ? 'selected' : ''?>>Aplikasi</option>
                    <option value="Jaringan" <?= $data['jenis_permasalahan'] == 'Jaringan' ? 'selected' : ''?>>Jaringan</option>
                </select>
            </div>
            <div class="mb-3">
                <label>Solusi Permasalahan</label>
                <textarea name="solusi_permasalahan" class="form-control" rows="3"><?= $data['solusi_permasalahan'] ?></textarea>
            </div>

            <div class="mb-3">
                <label>Status Laporan</label>
                <select name="status_laporan" class="form-select" required>
                    <option value="Selesai" <?= $data['status_laporan'] == 'Selesai' ? 'selected' : '' ?>>Selesai</option>
                    <option value="Proses" <?= $data['status_laporan'] == 'Proses' ? 'selected' : '' ?>>Proses</option>
                    <option value="Pending" <?= $data['status_laporan'] == 'Pending' ? 'selected' : '' ?>>Pending</option>
                </select>
            </div>

            <div class="mb-3">
                <label>Durasi</label>
                <input type="text" name="durasi" value="<?= $data['durasi'] ?>" class="form-control" required>
            </div>

            <button type="submit" name="update" class="btn btn-success">Simpan Perubahan</button>
            <a href="daftar_laporan.php" class="btn btn-secondary">Kembali</a>
        </form>
    </div>
<?php
include '../template/footer.php';
?>