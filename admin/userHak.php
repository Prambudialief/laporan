<?php
session_start();
require_once '../template_admin/header.php';

if (isset($_GET['set_role'])) {
    $id = intval($_GET['id']);
    $role = $_GET['set_role'];

    // role yang diizinkan
    if (!in_array($role, ['user', 'viewer'])) {
        die("Role tidak valid");
    }

    // pastikan tidak mengubah admin
    $cek = mysqli_query($conn, "SELECT role FROM user WHERE id = $id");
    $data = mysqli_fetch_assoc($cek);

    if ($data['role'] === 'admin') {
        die("Tidak boleh mengubah role admin");
    }

    mysqli_query($conn, "UPDATE user SET role = '$role' WHERE id = $id");
    header("Location: userHak.php");
    exit;
}

if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $query = "DELETE FROM user WHERE id = $id AND role IN ('user','viewer')";
    if (mysqli_query($conn, $query)) {
        header("Location: userHak.php");
        exit;
    } else {
        die("Gagal menghapus data: " . mysqli_error($conn));
    }
}
$result = mysqli_query($conn, "SELECT * FROM user WHERE role IN ('user','viewer')");
require_once '../template_admin/navbar.php';
require_once '../template_admin/sidebar.php';
?>
<div class="container mt-4">
    <h3 class="fw-bold mb-3 text-center">Daftar User</h3>
    <a id="exportBtn" href="excel_user.php" class="btn btn-outline-success mb-1 mt-1">Export By Excel</a>
    <div class="row mb-3">
        <div class="col-md-3">
            <label class="form-label fw-semibold">Tampilkan</label>
            <select id="rowsSelect" class="form-select">
                <option value="10" selected>10</option>
                <option value="25">25</option>
                <option value="50">50</option>
                <option value="all">Semua</option>
            </select>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-bordered table-striped align-middle text-center">
            <thead class="table-dark">
                <tr>
                    <th>No</th>
                    <th>User</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody id="table-data">
                <?php
                $no = 1;
                while ($row = mysqli_fetch_assoc($result)) { ?>

                    <tr>
                        <td><?= $no++ ?></td>
                        <td class="text-start"><?= htmlspecialchars($row['nama']) ?></td>
                        <td class="text-start"><?= htmlspecialchars($row['email']) ?></td>
                        <td class="text-start"><?= htmlspecialchars($row['role']) ?></td>
                        <td>
                            <form method="GET" class="d-inline">
                                <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                <select name="set_role" class="form-select form-select-sm"
                                    onchange="this.form.submit()">
                                    <option value="user" <?= $row['role'] === 'user' ? 'selected' : '' ?>>
                                        User
                                    </option>
                                    <option value="viewer" <?= $row['role'] === 'viewer' ? 'selected' : '' ?>>
                                        Viewer
                                    </option>
                                </select>
                            </form>

                            <a href="?delete=<?= $row['id'] ?>"
                                onclick="return confirm('Yakin ingin menghapus user ini?')"
                                class="btn btn-outline-danger btn-sm mt-1">
                                Hapus
                            </a>
                        </td>
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
    let rowsPerPage = 10;
    let currentPage = 1;

    const tableData = document.querySelectorAll("#table-data tr");
    const totalRows = tableData.length;

    const totalPages = Math.ceil(totalRows / rowsPerPage);

    function showPage(page) {
        currentPage = page;

        let start = (page - 1) * rowsPerPage;
        let end = rowsPerPage === totalRows ? totalRows : start + rowsPerPage;

        tableData.forEach((row, index) => {
            row.style.display = (index >= start && index < end) ? "" : "none";
        });

        renderPagination();
    }

    function renderPagination() {
        const pagination = document.getElementById("pagination");
        pagination.innerHTML = "";

        if (rowsPerPage === totalRows) return; // jika ALL → pagination disembunyikan

        const totalPages = Math.ceil(totalRows / rowsPerPage);

        for (let i = 1; i <= totalPages; i++) {
            pagination.innerHTML += `
            <li class="page-item ${i === currentPage ? "active" : ""}">
                <button class="page-link" onclick="showPage(${i})">${i}</button>
            </li>
        `;
        }
    }

    document.getElementById("rowsSelect").addEventListener("change", function() {
        const value = this.value;

        if (value === "all") {
            rowsPerPage = totalRows;
        } else {
            rowsPerPage = parseInt(value);
        }

        currentPage = 1;
        showPage(currentPage);
    });
    // Show first page when loading
    showPage(1);

    document.getElementById("exportBtn").addEventListener("click", function() {
        let limit = document.getElementById("rowsSelect").value;
        window.location.href = "excel_user.php?limit=" + limit + "&page=" + currentPage;
    });
</script>
<?php
include '../template_admin/footer.php'
?>