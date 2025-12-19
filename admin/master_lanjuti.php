<?php
include '../services/connection.php';

// ==== LOGIKA TAMBAH ====
if (isset($_POST['add'])) {
    $nama = mysqli_real_escape_string($conn, $_POST['nama_lanjuti']);
    $jabatan = mysqli_real_escape_string($conn, $_POST['jabatan']);
    mysqli_query($conn, "INSERT INTO master_lanjuti (nama_lanjuti, jabatan) VALUES ('$nama', '$jabatan')");
    header("Location: master_lanjuti.php");
    exit();
}

// ==== LOGIKA EDIT ====
if (isset($_POST['edit'])) {
    $id   = intval($_POST['id']);
    $nama = mysqli_real_escape_string($conn, $_POST['nama_lanjuti']);
    $jabatan = mysqli_real_escape_string($conn, $_POST['jabatan']);
    mysqli_query($conn, "UPDATE master_lanjuti SET nama_lanjuti='$nama', jabatan='$jabatan' WHERE id=$id");
    header("Location: master_lanjuti.php");
    exit();
}

// ==== LOGIKA HAPUS ====
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    mysqli_query($conn, "DELETE FROM master_lanjuti WHERE id=$id");
    header("Location: master_lanjuti.php");
    exit();
}

include '../template_admin/header.php';
include '../template_admin/navbar.php';
include '../template_admin/sidebar.php';
$lanjuti = mysqli_query($conn, "SELECT * FROM master_lanjuti ORDER BY id DESC");
?>
<div class="container mt-4">

    <h3 class="fw-bold mb-3 text-center">Tindak Lanjuti</h3>
    <a data-bs-toggle="modal" data-bs-target="#modalTambah" class="btn btn-outline-primary mb-1 mt-1">Tambah Tindak Lanjuti</a>
    <a id="exportBtn" class="btn btn-outline-success mb-1">Export by Excel</a>
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
                    <th>Nama</th>
                    <th>Jabatan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody id="table-data">
                <?php
                $no = 1;
                while ($row = mysqli_fetch_assoc($lanjuti)) { ?>

                    <tr>
                        <td><?= $no++ ?></td>
                        <td class="text-start"><?= htmlspecialchars($row['nama_lanjuti']) ?></td>
                        <td class="text-start"><?= htmlspecialchars($row['jabatan']) ?></td>
                        <td>
                            <button class="btn btn-outline-warning"
                                data-bs-toggle="modal"
                                data-bs-target="#modalEdit"
                                data-id="<?= $row['id'] ?>"
                                data-nama="<?= htmlspecialchars($row['nama_lanjuti']) ?>"
                                data-jabatan="<?= htmlspecialchars($row['jabatan']) ?>">
                                Edit
                            </button>

                            <a href="?delete=<?= $row['id'] ?>"
                                onclick="return confirm('Yakin Ingin Menghapus Permasalahan Ini?')"
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
                    <h5 class="modal-title">Tambah Jenis Tindak Lanjuti</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <label class="form-label">Nama Tindak Lanjuti</label>
                    <input type="text" name="nama_lanjuti" class="form-control" required>
                </div>

                <div class="modal-body">
                    <label class="form-label">Jabatan</label>
                    <input type="text" name="jabatan" class="form-control" required>
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
                    <h5 class="modal-title">Edit Tindak Lanjuti</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <input type="hidden" name="id" id="edit-id">

                    <label class="form-label">Nama Tindak Lanjuti</label>
                    <input type="text" name="nama_lanjuti" id="edit-nama" class="form-control" required>
                </div>

                <div class="modal-body">
                    <label class="form-label">Jabatan</label>
                    <input type="text" name="jabatan" id="edit-jabatan" class="form-control" required>
                </div>

                <div class="modal-footer">
                    <button type="submit" name="edit" class="btn btn-warning">Update</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                </div>
            </form>
        </div>
    </div>


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

        document.getElementById('edit-id').value = button.getAttribute('data-id');
        document.getElementById('edit-nama').value = button.getAttribute('data-nama');
        document.getElementById('edit-jabatan').value = button.getAttribute('data-jabatan');
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
        window.location.href = "excel_lanjuti.php?limit=" + limit + "&page=" + currentPage;
    });
</script>

<?php
include '../template_admin/footer.php';
?>