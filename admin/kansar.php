<?php
session_start();
require_once '../template_admin/header.php';
require_once '../template_admin/navbar.php';
require_once '../template_admin/sidebar.php';
?>
<div class="container mt-4">

<h5 class="fw-bold mb-3">Daftar Kantor Sar</h5>
<a id="exportExcel" href="excel_kansar.php" class="btn btn-outline-success mb-1 mt-1">Export By Excel</a>

<div class="table-responsive">
    <table class="table table-bordered table-striped align-middle text-center">
        <thead class="table-dark">
            <tr>
                <th>No</th>
                <th>Kantor Sar</th>
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
    "Banda Aceh",
    "Medan",
    "Pekanbaru",
    "Padang",
    "Tanjung Pinang",
    "Jambi",
    "Bengkulu",
    "Palembang",
    "Pangkal Pinang",
    "Lampung", 
    "Jakarta",
    "Bandung",
    "Semarang",
    "Surabaya",
    "Denpasar",
    "Mataram",
    "Kupang",
    "Pontianak",
    "Banjarmasin",
    "Balikpapan",
    "Makassar",
    "Kendari",
    "Palu",
    "Gorontalo",
    "Manado",
    "Ternate",
    "Ambon",
    "Sorong",
    "Manokwari",
    "Biak",
    "Timika",
    "Jayapura",
    "Merauke",
    "Banten",
    "Natuna",
    "Mentawai",
    "Maumere",
    "Yogyakarta",
    "Tarakan",
    "Palangkaraya",
    "Balai Diklat",
    "Iusar"
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
        "excel_kansar.php?page=" + currentPage;
    }

    // Load awal
    renderTable();
    renderPagination();
    updateExportLink();
</script>


<?php 
require_once '../template_admin/footer.php';
?>