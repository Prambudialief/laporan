<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include '../services/connection.php';

$limit = $_GET['limit'] ?? 10; 
$page  = isset($_GET['page']) ? intval($_GET['page']) : 1;
if ($page < 1) $page = 1;

// Set header untuk Excel
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=laporan_aplikasi_page_" . $page . ".xls");
header("Pragma: no-cache");
header("Expires: 0");

if ($limit === 'all') {

    $query = "SELECT nama_aplikasi FROM master_aplikasi ORDER BY id DESC";

} else {

    $limit  = intval($limit);
    $offset = ($page - 1) * $limit;

    $query = "SELECT nama_aplikasi 
              FROM master_aplikasi 
              ORDER BY id DESC 
              LIMIT $offset, $limit";
}

$result = mysqli_query($conn, $query);

// Mulai tabel
echo "<table border='1'>
<thead>
<tr style='background:#d9d9d9; font-weight:bold; text-align:center;'>
    <th>No</th>
    <th>Nama Aplikasi</th>
</tr>
</thead>
<tbody>";

$no = 1;

while ($row = mysqli_fetch_assoc($result)) {
    echo "<tr>
        <td style='text-align:center;'>$no</td>
        <td>{$row['nama_aplikasi']}</td>
    </tr>";
    $no++;
}

echo "</tbody></table>";

exit();
?>
