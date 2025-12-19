<?php
session_start();

include '../template_admin/header.php';
include '../template_admin/navbar.php';
include '../template_admin/sidebar.php';
include '../services/connection.php';


$tot = $conn->query("SELECT COUNT(*) AS jml FROM laporan")->fetch_assoc()['jml'];

$selesai = $conn->query("SELECT COUNT(*) AS jml FROM laporan WHERE status_laporan='Selesai'")->fetch_assoc()['jml'];

$proses = $conn->query("SELECT COUNT(*) AS jml FROM laporan WHERE status_laporan!='Selesai'")->fetch_assoc()['jml'];

$appQuery = $conn->query("
    SELECT nama_aplikasi, COUNT(*) AS total 
    FROM laporan 
    GROUP BY nama_aplikasi 
    ORDER BY total DESC
");


$statusQuery = $conn->query("
    SELECT status_laporan, COUNT(*) AS total 
    FROM laporan 
    GROUP BY status_laporan
");


$appNames = [];
$appCounts = [];
while ($row = $appQuery->fetch_assoc()) {
    $appNames[] = $row['nama_aplikasi'];
    $appCounts[] = $row['total'];
}

$statusLabels = [];
$statusData = [];
while ($row = $statusQuery->fetch_assoc()) {
    $statusLabels[] = $row['status_laporan'];
    $statusData[] = $row['total'];
}
?>

<style>
    .card-stat {
        border-radius: 15px;
        transition: .3s;
    }

    .card-stat:hover {
        transform: translateY(-5px);
        box-shadow: 0 6px 18px rgba(0, 0, 0, 0.1);
    }
</style>

<div class="container-fluid mt-4">

    <h3 class="fw-bold mb-4 text-center">Dashboard Statistik</h3>

    <!-- STATISTICS CARD -->
    <div class="row g-3 d-flex justify-content-center align-items-center">

        <div class="col-md-3 col-sm-6">
            <div class="card card-stat shadow-sm border-primary">
                <div class="card-body text-center">
                    <h6 class="text-muted">Total Laporan Masuk</h6>
                    <h3 class="text-primary fw-bold"><?= $tot ?></h3>
                </div>
            </div>
        </div>

        <div class="col-md-3 col-sm-6">
            <div class="card card-stat shadow-sm border-success">
                <div class="card-body text-center">
                    <h6 class="text-muted">Laporan Selesai</h6>
                    <h3 class="text-success fw-bold"><?= $selesai ?></h3>
                </div>
            </div>
        </div>

        <div class="col-md-3 col-sm-6">
            <div class="card card-stat shadow-sm border-warning">
                <div class="card-body text-center">
                    <h6 class="text-muted">Laporan Diproses</h6>
                    <h3 class="text-warning fw-bold"><?= $proses ?></h3>
                </div>
            </div>
        </div>

    </div>

    <!-- CHART SECTION -->
    <div class="row mt-4">

        <!-- Grafik laporan per aplikasi -->
        <div class="col-lg-8 mb-2">
            <div class="card shadow-sm">
                <div class="card-header text-center bg-info text-white fw-bold">Grafik Laporan per Aplikasi</div>
                <div class="card-body">

                    <!-- SCROLLABLE CHART -->
                    <div style="overflow-x: auto; white-space: nowrap;">
                        <canvas id="chartApps" style="min-width: 1200px; height: 400px;"></canvas>
                    </div>

                </div>
            </div>
        </div>

        <!-- Grafik pie status -->
        <div class="col-lg-4">
            <div class="card shadow-sm">
                <div class="card-header text-center bg-info text-white fw-bold">Status Laporan</div>
                <div class="card-body">
                    <canvas id="chartStatus" height="200"></canvas>
                </div>
            </div>
        </div>

    </div>
</div>

<!-- CHART.JS -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>

// -------------------------
// RANDOM COLOR GENERATOR
// -------------------------
function generateColors(count) {
    let colors = [];
    for (let i = 0; i < count; i++) {
        const r = Math.floor(Math.random()*255);
        const g = Math.floor(Math.random()*255);
        const b = Math.floor(Math.random()*255);
        colors.push(`rgba(${r}, ${g}, ${b}, 0.6)`);
    }
    return colors;
}

// -------------------------
// WRAP LABEL NAMA APLIKASI
// -------------------------
function wrapLabel(label) {
    return label.match(/.{1,14}/g); // potong tiap 14 huruf
}

// -------------------------
// BAR CHART APPS (DINAMIS)
// -------------------------
var ctxApp = document.getElementById('chartApps');

new Chart(ctxApp, {
    type: 'bar',
    data: {
        labels: <?= json_encode($appNames) ?>,
        datasets: [{
            label: "Jumlah Laporan",
            data: <?= json_encode($appCounts) ?>,
            borderWidth: 1,
            backgroundColor: generateColors(<?= count($appNames) ?>)
        }]
    },
    options: {
        indexAxis: 'y', // <--- INI YANG BIKIN HORISONTAL
        responsive: true,
        maintainAspectRatio: false,
        scales: {
                x: {
                    beginAtZero: true,
                    ticks: {
                        precision: 0, // HILANGKAN DESIMAL
                        callback: function(value) {
                            return Number.isInteger(value) ? value : '';
                        }
                    }
                },
                y: {
                    ticks: {
                        callback: function(value) {
                            const label = this.getLabelForValue(value);
                            return label.length > 20 ?
                                label.substring(0, 20) + "..." :
                                label;
                        }
                    }
                }
            }
    }
});


// -------------------------
// PIE STATUS
// -------------------------
var ctxStatus = document.getElementById('chartStatus');
new Chart(ctxStatus, {
    type: 'pie',
    data: {
        labels: <?= json_encode($statusLabels) ?>,
        datasets: [{
            data: <?= json_encode($statusData) ?>,
            backgroundColor: generateColors(<?= count($statusLabels) ?>)
        }]
    },
    options: {
        responsive: true
    }
});
</script>

<?php include '../template_admin/footer.php'; ?>
