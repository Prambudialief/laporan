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
    <h3 class="fw-bold">Tambah Laporan</h3>
    <div class="container-fluid p-4 mt-4">
        <div class="card">
            <div class="card-body form-container">
                <form method="post" enctype="multipart/form-data" action="sistemLaporan.php">
                    <div class="row g-4">
                        <div class="col-12 col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Tambah Laporan</label>
                                <input type="text" class="form-control" name="judul_laporan" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Nama Aplikasi</label>
                                <select class="form-select" name="nama_aplikasi" required size="4" style="overflow-y: auto; max-height: 200px;">
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
                            <div class="mb-3">
                                <label class="form-label">Jenis Permasalahan</label>
                                <select class="form-select" name="jenis_permasalahan" required style="overflow-y: auto; max-height: 70px;">
                                    <option value="Hacking">Hacking</option>
                                    <option value="Down time">Down Time</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Kantor SAR/Unit Kerja</label>
                                <select class="form-select" name="kantor_sar" required size="4" style="overflow-y: auto; max-height: 200px;">
                                    <option value="">-- Pilih Kantor Sar --</option>
                                    <option value="Kantor Pusat">Kantor Pusat</option>
                                    <option value="Banda Aceh">Banda Aceh</option>
                                    <option value="Medan">Medan</option>
                                    <option value="Pekanbaru">Pekanbaru</option>
                                    <option value="Padang">Padang</option>
                                    <option value="Tanjung Pinang">Tanjung Pinang</option>
                                    <option value="Jambi">Jambi</option>
                                    <option value="Bengkulu">Bengkulu</option>
                                    <option value="Palembang">Palembang</option>
                                    <option value="Pangkal Pinang">Pangkal Pinang</option>
                                    <option value="Lampung">Lampung</option>
                                    <option value="Jakarta">Jakarta</option>
                                    <option value="Bandung">Bandung</option>
                                    <option value="Semarang">Semarang</option>
                                    <option value="Surabaya">Surabaya</option>
                                    <option value="Denpasar">Denpasar</option>
                                    <option value="Mataram">Mataram</option>
                                    <option value="Kupang">Kupang</option>
                                    <option value="Pontianak">Pontianak</option>
                                    <option value="Banjarmasin">Banjarmasin</option>
                                    <option value="Balikpapan">Jakarta</option>
                                    <option value="Makassar">Makassar</option>
                                    <option value="Kendari">Kendari</option>
                                    <option value="Palu">Palu</option>
                                    <option value="Gorontalo">Gorontalo</option>
                                    <option value="Manado">Manado</option>
                                    <option value="Ternate">Ternate</option>
                                    <option value="Ambon">Ambon</option>
                                    <option value="Sorong">Sorong</option>
                                    <option value="Manokwari">Manokwari</option>
                                    <option value="Biak">Biak</option>
                                    <option value="Timika">Timika</option>
                                    <option value="Jayapura">Jayapura</option>
                                    <option value="Merauke">Merauke</option>
                                    <option value="Banten">Banten</option>
                                    <option value="Natuna">Natuna</option>
                                    <option value="Mentawai">Mentawai</option>
                                    <option value="Maumere">Maumere</option>
                                    <option value="Yogyakarta">Yogyakarta</option>
                                    <option value="Tarakan">Tarakan</option>
                                    <option value="Palangkaraya">Palangkaraya</option>
                                    <option value="Balai Diklat">Balai Diklat</option>
                                    <option value="Iusar">Iusar</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Unit Kerja</label>
                                <select class="form-select" name="unit_kerja" required size="4" style="overflow-y: auto; max-height: 200px;">
                                    <option value="">-- Pilih Unit Kerja</option>
                                    <option value="Humas">Humas</option>
                                    <option value="Pusdatin">Pusdatin</option>
                                    <option value="ULP">ULP</option>
                                    <option value="Kepegawain">Kepegawaian</option>
                                    <option value="Perencanaan">Perencanaan</option>
                                    <option value="Biro Hukum & Kepegawain">Biro Hukum & Kepegawaian</option>
                                    <option value="Dit. Operasi dan Dit. Kesiapsiagaan">Dit. Operasi dan Dit. Kesiapsiagaan</option>
                                    <option value="Biro Umum">Biro Umum</option>
                                    <option value="Inspektorat">Inspektorat</option>
                                    <option value="Balai Diklat & Puslat SDM">Balai Diklat & Puslat SDM</option>
                                    <option value="Dit Kesiapsiagaan">Dit Kesiapsiagaan</option>
                                    <option value="Biro Humas dan Umum">Biro Humas dan Umum (arsip)</option>
                                    <option value="Dit. Binpot">Dit. Binpot</option>
                                    <option value="Dit. Operasi">Dit. Operasi</option>
                                    <option value="Dit. Komunikasi">Dit. Komunikasi</option>
                                    <option value="Perencanaan KTLN">Perencanaan KTLN</option>
                                    <option value="Biro Kepegawaian & Ortala">Biro Kepegawaian & Ortala</option>
                                    <option value="Dharma Wanita Persatuan">Dharma Wanita Persatuan</option>
                                    <option value="Deputi Bidang Operasi Pencarian dan Pertolongan, dan kesiapsiagaan">Deputi Bidang Operasi Pencarian dan Pertolongan, dan kesiapsiagaan</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Nama Pelapor</label>
                                <input type="text" class="form-control" name="nama_pelapor" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Media Pelaporan</label>
                                <select class="form-select" name="media_pelaporan" required>
                                    <option value="">-- Pilih Media --</option>
                                    <option value="Whatsapp">Whatsapp</option>
                                    <option value="Email">Email</option>
                                    <option value="Telepon">Telepon</option>
                                </select>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Waktu Pelaporan</label>
                                <input type="datetime-local" class="form-control" name="waktu_pelaporan" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Nama Petugas</label>
                                <input type="text" class="form-control" name="nama_petugas"
                                    value="<?php echo htmlspecialchars($_SESSION['nama']); ?>" readonly>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Tanggal Pemutakhiran</label>
                                <input type="datetime-local" class="form-control" name="tanggal_pemutakhiran" required>
                            </div>
                        </div>

                        <!-- Kolom Kanan -->
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Deskripsi Permasalahan</label>
                                <textarea class="form-control" name="deskripsi_permasalahan" rows="3" required></textarea>
                                <input class="form-control mt-3" type="file" name="gambar_deskripsi" id="gambar_deskripsi">
                                <p class="text-danger">Maksimal Ukuran File 2MB</p>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Solusi Permasalahan</label>
                                <textarea class="form-control" name="solusi_permasalahan" rows="3" required></textarea>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Tanggal Penyelesaian</label>
                                <input type="datetime-local" class="form-control" name="tanggal_penyelesaian" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Status Laporan</label>
                                <select class="form-select" name="status_laporan" required>
                                    <option value="">-- Pilih Status --</option>
                                    <option value="Selesai">Selesai</option>
                                    <option value="Proses">Proses</option>
                                    <option value="Pending">Pending</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Durasi</label>
                                <input type="text" class="form-control" name="durasi_tampil" id="durasi_tampil" placeholder="Akan dihitung otomatis" readonly>
                                <input type="hidden" name="durasi" id="durasi">
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