<?php
session_start();
require_once '../template_admin/header.php';
require_once '../template_admin/navbar.php';
require_once '../template_admin/sidebar.php';
?>
<div class="container mt-4">

<h5 class="fw-bold mb-3">Daftar Unit Kerja</h5>

<a id="exportExcel" href="excel_unitkerja.php" class="btn btn-outline-success mb-1 mt-1"> Export By Excel</a>

<div class="table-responsive">
    <table class="table table-bordered table-striped align-middle text-center">
        <thead class="table-dark">
            <tr>
                <th>No</th>
                <th>Kantor Unit Kerja</th>
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
                    "Humas",
                    "Pusdatin",
                    "ULP",
                    "Kepegawain",
                    "Perencanaan",
                    "Biro Hukum & Kepegawain",
                    "Dit. Operasi dan Dit. Kesiapsiagaan",
                    "Biro Umum",
                    "Inspektorat",
                    "Balai Diklat & Puslat SDM",
                    "Dit Kesiapsiagaan",
                    "Biro Humas dan Umum",
                    "Dit. Binpot",
                    "Dit. Operasi",
                    "Dit. Komunikasi",
                    "Perencanaan KTLN",
                    "Biro Kepegawaian & Ortala",
                    "Dharma Wanita Persatuan",
                    "Deputi Bidang Operasi Pencarian dan Pertolongan, dan kesiapsiagaan"
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
        document.getElementById("exportExcel").href=
        "excel_unitkerja.php?page=" + currentPage;
    }

    // Load awal
    renderTable();
    renderPagination();
    updateExportLink();
</script>


<?php 
require_once '../template_admin/footer.php';
?>