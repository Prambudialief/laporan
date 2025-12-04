<?php
session_start();
$user_id = $_SESSION['user_id'];
include '../template/header.php';
include '../template/navbar.php';
include '../template/sidebar.php';
include '../services/connection.php';

$tot = $conn->query("SELECT COUNT(*) AS jml FROM laporan WHERE user_id='$user_id'")->fetch_assoc()['jml'];

$selesai = $conn->query("SELECT COUNT(*) AS jml FROM laporan WHERE status_laporan='Selesai' AND user_id='$user_id'")->fetch_assoc()['jml'];

$proses = $conn->query("SELECT COUNT(*) AS jml FROM laporan WHERE status_laporan!='Selesai' AND user_id='$user_id'")->fetch_assoc()['jml'];

$appQuery = $conn->query("SELECT nama_aplikasi, COUNT(*) AS total FROM laporan WHERE user_id='$user_id' GROUP BY nama_aplikasi ORDER BY total DESC LIMIT 10");

$statusQuery = $conn->query("SELECT status_laporan, COUNT(*) AS total FROM laporan WHERE user_id='$user_id' GROUP BY status_laporan");

$appNames = [];
$appCounts = [];
while ($row = $appQuery->fetch_assoc()) {
    $appNames[] = $row['nama_aplikasi'];
    $appCounts[] = $row['total'];
}

$statusQuery = $conn->query("SELECT status_laporan, COUNT(*) AS total FROM laporan GROUP BY status_laporan");

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
    box-shadow: 0 6px 18px rgba(0,0,0,0.1);
}
</style>

<div class="container-fluid mt-4">

    <h3 class="fw-bold mb-4">Dashboard Statistik</h3>

    <!-- STATISTICS CARD -->
    <div class="row g-3">

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
                <div class="card-header bg-info text-white fw-bold">Grafik Laporan per Aplikasi</div>
                <div class="card-body">
                    <canvas id="chartApps" height="150"></canvas>
                </div>
            </div>
        </div>

        <!-- Grafik pie status -->
        <div class="col-lg-4">
            <div class="card shadow-sm">
                <div class="card-header bg-info text-white fw-bold">Status Laporan</div>
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
    // Plugin untuk memaksa label horizontal
const FixLabelRotation = {
    id: 'FixLabelRotation',
    beforeDraw(chart) {
        if (chart.options.scales?.x?.ticks) {
            chart.options.scales.x.ticks.maxRotation = 0;
            chart.options.scales.x.ticks.minRotation = 0;
        }
    }
};

var ctxApp = document.getElementById('chartApps');
new Chart(ctxApp, {
    type: 'bar',
    data: {
        labels: <?= json_encode($appNames) ?>,
        datasets: [{
            label: "Jumlah Laporan",
            data: <?= json_encode($appCounts) ?>,
            borderWidth: 1,
            backgroundColor: function(context) {
                const colors = [
                    "rgba(54, 162, 235, 0.6)",
                    "rgba(255, 99, 132, 0.6)",
                    "rgba(255, 205, 86, 0.6)",
                    "rgba(75, 192, 192, 0.6)",
                    "rgba(153, 102, 255, 0.6)",
                    "rgba(255, 159, 64, 0.6)"
                ];
                return colors[context.dataIndex % colors.length];
            },
            borderColor: function(context) {
                const borders = [
                    "rgba(54, 162, 235, 1)",
                    "rgba(255, 99, 132, 1)",
                    "rgba(255, 205, 86, 1)",
                    "rgba(75, 192, 192, 1)",
                    "rgba(153, 102, 255, 1)",
                    "rgba(255, 159, 64, 1)"
                ];
                return borders[context.dataIndex % borders.length];
            }
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        scales: {
            x: {
                ticks: {
                    maxRotation: 0,
                    minRotation: 0,
                    autoSkip: false,
                    callback: function(value) {
                        const label = this.getLabelForValue(value);
                        const maxLength = 14;
                        if (label.length > maxLength) {
                            return label.match(new RegExp('.{1,' + maxLength + '}', 'g'));
                        }
                        return label;
                    }
                }
            },
            y: {
                beginAtZero: true
            }
        }
    },
    plugins: [FixLabelRotation]
});


var ctxStatus = document.getElementById('chartStatus');
new Chart(ctxStatus, {
    type: 'pie',
    data: {
        labels: <?= json_encode($statusLabels) ?>,
        datasets: [{
            data: <?= json_encode($statusData) ?>,
        }]
    },
    options: {
    responsive: true,
    maintainAspectRatio: true,
    scales: {
        x: {
            ticks: {
                maxRotation: 0,
                minRotation: 0,
                autoSkip: false,  
                callback: function(value, index) {
                    let label = this.getLabelForValue(value);

                    // Break label per 10 karakter (agar rapi di mobile)
                    let words = label.match(/.{1,10}/g);
                    return words;
                }
            }
        },
        y: {
            beginAtZero: true
        }
    }
}

});
</script>

<?php include '../template/footer.php'; ?>
