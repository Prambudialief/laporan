<?php
session_start();
require_once '../template_admin/header.php';
require_once '../template_admin/navbar.php';
require_once '../template_admin/sidebar.php';
?>
<div class="container mt-4">

    <h5 class="fw-bold mb-3">Daftar Aplikasi</h5>
    <a id="exportExcel" href="excel_aplikasi.php" class="btn btn-outline-success mt-1 mb-1">Export By Excel</a>

    <div class="table-responsive">
        <table class="table table-bordered table-striped align-middle text-center">
            <thead class="table-dark">
                <tr>
                    <th>No</th>
                    <th>Nama Aplikasi</th>
                </tr>
            </thead>
            <tbody id="appTable">
                <!-- Data akan di-load dengan JavaScript -->
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <nav>
        <ul class="pagination justify-content-center" id="pagination">
            <!-- Auto generate -->
        </ul>
    </nav>

</div>
<script>
    // Data aplikasi (ambil dari option yang kamu berikan)
    const aplikasi = [
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

    const rowsPerPage = 10;
    let currentPage = 1;

    function renderTable(page = 1) {
        const tableBody = document.getElementById("appTable");
        tableBody.innerHTML = "";

        let start = (page - 1) * rowsPerPage;
        let end = start + rowsPerPage;

        aplikasi.slice(start, end).forEach((app, index) => {
            tableBody.innerHTML += `
                <tr>
                    <td>${start + index + 1}</td>
                    <td>${app}</td>
                </tr>
            `;
        });
    }

    function renderPagination() {
        const totalPages = Math.ceil(aplikasi.length / rowsPerPage);
        const pagination = document.getElementById("pagination");

        pagination.innerHTML = "";

        for (let i = 1; i <= totalPages; i++) {
            pagination.innerHTML += `
                <li class="page-item ${i === currentPage ? "active" : ""}">
                    <button class="page-link" onclick="goToPage(${i})">${i}</button>
                </li>
            `;
        }
    }

    function goToPage(page) {
        currentPage = page;
        renderTable(page);
        renderPagination();
        updateExportLink();
    }

    function updateExportLink() {
        document.getElementById("exportExcel").href =
        "excel_aplikasi.php?page=" + currentPage;
    }

    // Load awal
    renderTable();
    renderPagination();
    updateExportLink();
</script>


<?php
require_once '../template_admin/footer.php';
?>