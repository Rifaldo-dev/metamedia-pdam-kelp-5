<?php
$pageTitle = "Laporan Pendapatan Tertinggi - PDAM Zernih";
$activePage = "laporan";
$activeSubPage = "tertinggi";
$baseUrl = "../";
include '../assets/layouts/header.php';
include '../koneksi.php';

$tahunFilter = isset($_GET['tahun']) ? (int)$_GET['tahun'] : (int)date('Y');
$limitFilter = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;

// Query pelanggan dengan pendapatan tertinggi
$query = "SELECT p.noRekening, p.namaPelanggan, k.namaKategori, 
                 COUNT(t.id) as jumlahBulan, 
                 SUM(t.pemakaian) as totalPemakaian, 
                 SUM(t.totalTagihan) as totalPendapatan
          FROM tagihan t 
          JOIN pelanggan p ON t.pelangganId = p.id 
          LEFT JOIN kategori k ON p.kategoriId = k.id 
          WHERE t.periodeTahun = $tahunFilter 
          GROUP BY t.pelangganId 
          ORDER BY totalPendapatan DESC 
          LIMIT $limitFilter";
$result = mysqli_query($conn, $query);

// Siapkan data chart
$chartNames = [];
$chartTotals = [];
$chartTempResult = mysqli_query($conn, $query);
while ($row = mysqli_fetch_assoc($chartTempResult)) {
    $chartNames[] = $row['namaPelanggan'];
    $chartTotals[] = (int)$row['totalPendapatan'];
}
?>

<?php include '../assets/layouts/sidebar.php'; ?>

    <!-- Content Header -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Laporan Pendapatan Tertinggi</h1>
                </div>
            </div>
        </div>
    </div>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header">
                    <form method="GET" class="form-inline">
                        <div class="form-group mr-2">
                            <label class="mr-2">Tahun:</label>
                            <select name="tahun" class="form-control form-control-sm">
                                <?php for ($y = date('Y'); $y >= 2020; $y--): ?>
                                    <option value="<?= $y ?>" <?= $y == $tahunFilter ? 'selected' : '' ?>><?= $y ?></option>
                                <?php endfor; ?>
                            </select>
                        </div>
                        <div class="form-group mr-2">
                            <label class="mr-2">Top:</label>
                            <select name="limit" class="form-control form-control-sm">
                                <option value="5" <?= $limitFilter == 5 ? 'selected' : '' ?>>5</option>
                                <option value="10" <?= $limitFilter == 10 ? 'selected' : '' ?>>10</option>
                                <option value="20" <?= $limitFilter == 20 ? 'selected' : '' ?>>20</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary btn-sm mr-2"><i class="fas fa-filter"></i> Filter</button>
                        <a href="exportExcel.php?tipe=tertinggi&tahun=<?= $tahunFilter ?>&limit=<?= $limitFilter ?>" class="btn btn-success btn-sm">
                            <i class="fas fa-file-excel"></i> Export Excel
                        </a>
                    </form>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <strong>Top <?= $limitFilter ?> Pelanggan dengan Pendapatan Tertinggi - Tahun <?= $tahunFilter ?></strong>
                    </div>
                    <!-- Chart Pendapatan Tertinggi -->
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <div class="card card-outline card-warning">
                                <div class="card-header">
                                    <h3 class="card-title"><i class="fas fa-chart-bar"></i> Top <?= $limitFilter ?> Pelanggan - Pendapatan Tertinggi</h3>
                                </div>
                                <div class="card-body">
                                    <canvas id="chartTertinggi" height="80"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>Peringkat</th>
                                    <th>No Rekening</th>
                                    <th>Pelanggan</th>
                                    <th>Kategori</th>
                                    <th>Jumlah Bulan</th>
                                    <th>Total Pemakaian (m³)</th>
                                    <th>Total Pendapatan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (mysqli_num_rows($result) > 0): ?>
                                    <?php $no = 1; while ($row = mysqli_fetch_assoc($result)): ?>
                                    <tr>
                                        <td>
                                            <?php if ($no <= 3): ?>
                                                <span class="badge badge-warning">#<?= $no ?></span>
                                            <?php else: ?>
                                                <?= $no ?>
                                            <?php endif; ?>
                                        </td>
                                        <td><?= $row['noRekening'] ?></td>
                                        <td><?= htmlspecialchars($row['namaPelanggan']) ?></td>
                                        <td><?= htmlspecialchars($row['namaKategori'] ?? '-') ?></td>
                                        <td><?= $row['jumlahBulan'] ?></td>
                                        <td><?= $row['totalPemakaian'] ?></td>
                                        <td>Rp <?= number_format($row['totalPendapatan'], 0, ',', '.') ?></td>
                                    </tr>
                                    <?php $no++; endwhile; ?>
                                <?php else: ?>
                                    <tr><td colspan="7" class="text-center">Tidak ada data untuk tahun ini</td></tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>

</div>
<!-- Chart.js -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const names = <?= json_encode($chartNames) ?>;
    const totals = <?= json_encode($chartTotals) ?>;

    // Generate gradient colors
    const colors = names.map(function(_, i) {
        const hue = (i * 35 + 200) % 360;
        return 'hsla(' + hue + ', 70%, 55%, 0.8)';
    });

    new Chart(document.getElementById('chartTertinggi'), {
        type: 'bar',
        data: {
            labels: names,
            datasets: [{
                label: 'Total Pendapatan (Rp)',
                data: totals,
                backgroundColor: colors,
                borderWidth: 1
            }]
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return 'Rp ' + context.raw.toLocaleString('id-ID');
                        }
                    }
                }
            },
            scales: {
                x: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return 'Rp ' + value.toLocaleString('id-ID');
                        }
                    }
                }
            }
        }
    });
});
</script>
<?php include '../assets/layouts/footer.php'; ?>
