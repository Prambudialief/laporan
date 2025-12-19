<?php
session_start();
include '../services/connection.php';
if (isset($_POST['add'])) {
    $nama = mysqli_real_escape_string($conn, $_POST['kantor_sar']);
    mysqli_query($conn, "INSERT INTO master_kantor_sar (kantor_sar) VALUES ('$nama')");
    header("Location: kansar.php");
    exit();
}

// ==== LOGIKA PROSES EDIT ====
if (isset($_POST['edit'])) {
    $id   = intval($_POST['id']);
    $nama = mysqli_real_escape_string($conn, $_POST['kantor_sar']);
    mysqli_query($conn, "UPDATE master_kantor_sar SET kantor_sar='$nama' WHERE id=$id");
    header("Location: kansar.php");
    exit();
}

// ==== LOGIKA PROSES HAPUS ====
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    mysqli_query($conn, "DELETE FROM master_kantor_sar WHERE id=$id");
    header("Location: kansar.php");
    exit();
}
require_once '../template_admin/header.php';
require_once '../template_admin/navbar.php';
require_once '../template_admin/sidebar.php';
$kansar = mysqli_query($conn, "SELECT * FROM master_kantor_sar ORDER BY id DESC");
?>
<div class="container mt-4">

    <h3 class="fw-bold mb-3 text-center">Daftar Satker</h3>
    <a data-bs-toggle="modal" data-bs-target="#modalTambah" class="btn btn-outline-primary mt-1 mb-1">Tambah Satker</a>
    <a id="exportBtn" class="btn btn-outline-success mb-1 mt-1">Export By Excel</a>
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
                    <th>Satker</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody id="table-data">
                <?php
                $no = 1;
                while ($row = mysqli_fetch_assoc($kansar)) { ?>

                    <tr>
                        <td><?= $no++ ?></td>
                        <td class="text-start"><?= htmlspecialchars($row['kantor_sar']) ?></td>
                        <td>
                            <button class="btn btn-outline-warning"
                                data-bs-toggle="modal"
                                data-bs-target="#modalEdit"
                                data-id="<?= $row['id'] ?>"
                                data-nama="<?= htmlspecialchars($row['kantor_sar']) ?>">
                                Edit
                            </button>
                            <a href="?delete=<?= $row['id'] ?>"
                                onclick="return confirm('Yakin Ingin Menghapus Kantor Sar Ini?')"
                                class="btn btn-outline-danger">Hapus
                            </a>
                        </td>
                    </tr>

                <?php
                }
                ?>
            </tbody>
        </table>
    </div>
    <div class="modal fade" id="modalTambah" tabindex="-1">
        <div class="modal-dialog">
            <form method="POST" class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">Tambah Satker</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <label class="form-label">Nama Satker</label>
                    <input type="text" name="kantor_sar" class="form-control" required>
                </div>

                <div class="modal-footer">
                    <button type="submit" name="add" class="btn btn-primary">Simpan</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                </div>
            </form>
        </div>
    </div>


    <!-- ========== MODAL EDIT ========== -->
    <div class="modal fade" id="modalEdit" tabindex="-1">
        <div class="modal-dialog">
            <form method="POST" class="modal-content">
                <div class="modal-header bg-warning">
                    <h5 class="modal-title">Edit Satker</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <input type="hidden" name="id" id="edit-id">

                    <label class="form-label">Nama Satker</label>
                    <input type="text" name="kantor_sar" id="edit-nama" class="form-control" required>
                </div>

                <div class="modal-footer">
                    <button type="submit" name="edit" class="btn btn-warning">Update</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Pagination -->
    <nav>
        <ul class="pagination justify-content-center" id="pagination">
            <!-- Auto generate -->
        </ul>
    </nav>

</div>
<script>
    var modalEdit = document.getElementById('modalEdit');
    modalEdit.addEventListener('show.bs.modal', function(event) {
        var button = event.relatedTarget;
        var id = button.getAttribute('data-id');
        var nama = button.getAttribute('data-nama');

        document.getElementById('edit-id').value = id;
        document.getElementById('edit-nama').value = nama;
    });
</script>
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
        window.location.href = "excel_kansar.php?limit=" + limit + "&page=" + currentPage;
    });
</script>


<?php
require_once '../template_admin/footer.php';
?>