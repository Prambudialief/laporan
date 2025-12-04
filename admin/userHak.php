<?php
session_start();
require_once '../template_admin/header.php';
$result = mysqli_query($conn, "SELECT * FROM user WHERE role = 'user'");
require_once '../template_admin/navbar.php';
require_once '../template_admin/sidebar.php';
?>
<div class="container mt-4">
    <h5 class="fw-bold mb-3">Daftar User</h5>
    <a id="exportBtn" href="excel_user.php" class="btn btn-outline-success mb-1 mt-1">Export By Excel</a>
    <div class="table-responsive">
        <table class="table table-bordered table-striped align-middle text-center">
            <thead class="table-dark">
                <tr>
                    <th>No</th>
                    <th>User</th>
                    <th>Email</th>
                    <th>Role</th>
                </tr>
            </thead>
            <tbody id="table-data">
                <?php
                $no = 1;
                while ($row = mysqli_fetch_assoc($result)) { ?>

                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= htmlspecialchars($row['nama']) ?></td>
                        <td><?= htmlspecialchars($row['email']) ?></td>
                        <td><?= htmlspecialchars($row['role']) ?></td>
                    </tr>

                <?php
                }
                ?>
            </tbody>
        </table>
    </div>
    <nav>
        <ul class="pagination justify-content-center" id="pagination"></ul>
    </nav>
</div>
<script>
    const rowsPerPage = 10;
    let currentPage = 1;

    const tableData = document.querySelectorAll("#table-data tr");
    const totalRows = tableData.length;
    const totalPages = Math.ceil(totalRows / rowsPerPage);

    function showPage(page) {
        currentPage = page;

        let start = (page - 1) * rowsPerPage;
        let end = start + rowsPerPage;

        tableData.forEach((row, index) => {
            row.style.display = (index >= start && index < end) ? "" : "none";
        });

        renderPagination();
    }

    function renderPagination() {
        const pagination = document.getElementById("pagination");
        pagination.innerHTML = "";

        // Page numbers
        for (let i = 1; i <= totalPages; i++) {
            pagination.innerHTML += `
                <li class="page-item ${i === currentPage ? "active" : ""}">
                    <button class="page-link" onclick="showPage(${i})">${i}</button>
                </li>
            `;
        }
    }

    // Show first page when loading
    showPage(1);

    document.getElementById("exportBtn").addEventListener("click", function() {
        window.location.href = "excel_permasalahan.php?page=" + currentPage;
    });
</script>
<?php
include '../template_admin/footer.php'
?>