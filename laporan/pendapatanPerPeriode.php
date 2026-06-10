<?php
$pageTitle = "Laporan Pendapatan per Periode - PDAM Zernih";
$activePage = "laporan";
$activeSubPage = "periode";
$baseUrl = "../";
include '../assets/layouts/header.php';
include '../koneksi.php';

// Filter
$bulanFilter = isset($_GET['bulan']) ? (int)$_GET['bulan'] : (int)date('m');
$tahunFilter = isset($_GET['tahun']) ? (int)$_GET['tahun'] : (int)date('Y');

// Query laporan
$query = "SELECT t.*, p.noRekening, p.namaPelanggan, k.namaKategori
          FROM tagihan t 
          JOIN pelanggan p ON t.pelangganId = p.id 
          LEFT JOIN kategori k ON p.kategoriId = k.id 
          WHERE t.periodeBulan = $bulanFilter AND t.periodeTahun = $tahunFilter 
          ORDER BY t.id DESC";
$result = mysqli_query($conn, $query);

// Total pendapatan
$totalPendapatan = mysqli_fetch_assoc(mysqli_query($conn, 
    "SELECT COALESCE(SUM(totalTagihan), 0) as total FROM tagihan WHERE periodeBulan = $bulanFilter AND periodeTahun = $tahunFilter"))['total'];

// Data chart status bayar
$statusQuery = mysqli_query($conn, 
    "SELECT statusBayar, COUNT(*) as jumlah, SUM(totalTagihan) as total 
     FROM tagihan WHERE periodeBulan = $bulanFilter AND periodeTahun = $tahunFilter 
     GROUP BY statusBayar");
$chartStatus = [];
while ($s = mysqli_fetch_assoc($statusQuery)) {
    $chartStatus[$s['statusBayar']] = ['jumlah' => (int)$s['jumlah'], 'total' => (int)$s['total']];
}

// Data chart per kategori
$kategoriQuery = mysqli_query($conn,
    "SELECT k.namaKategori, COUNT(t.id) as jumlah, SUM(t.totalTagihan) as total
     FROM tagihan t 
     JOIN pelanggan p ON t.pelangganId = p.id 
     LEFT JOIN kategori k ON p.kategoriId = k.id
     WHERE t.periodeBulan = $bulanFilter AND t.periodeTahun = $tahunFilter
     GROUP BY k.namaKategori ORDER BY total DESC");
$chartKategoriLabels = [];
$chartKategoriData = [];
while ($k = mysqli_fetch_assoc($kategoriQuery)) {
    $chartKategoriLabels[] = $k['namaKategori'] ?? 'Tanpa Kategori';
    $chartKategoriData[] = (int)$k['total'];
}

$bulanNama = ['','Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
?>

<?php include '../assets/layouts/sidebar.php'; ?>

    <!-- Content Header -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Laporan Pendapatan per Periode</h1>
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
                            <label class="mr-2">Bulan:</label>
                            <select name="bulan" class="form-control form-control-sm">
                                <?php for ($i = 1; $i <= 12; $i++): ?>
                                    <option value="<?= $i ?>" <?= $i == $bulanFilter ? 'selected' : '' ?>><?= $bulanNama[$i] ?></option>
                                <?php endfor; ?>
                            </select>
                        </div>
                        <div class="form-group mr-2">
                            <label class="mr-2">Tahun:</label>
                            <select name="tahun" class="form-control form-control-sm">
                                <?php for ($y = date('Y'); $y >= 2020; $y--): ?>
                                    <option value="<?= $y ?>" <?= $y == $tahunFilter ? 'selected' : '' ?>><?= $y ?></option>
                                <?php endfor; ?>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary btn-sm mr-2"><i class="fas fa-filter"></i> Filter</button>
                        <a href="exportExcel.php?tipe=periode&bulan=<?= $bulanFilter ?>&tahun=<?= $tahunFilter ?>" class="btn btn-success btn-sm">
                            <i class="fas fa-file-excel"></i> Export Excel
                        </a>
                    </form>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <strong>Periode:</strong> <?= $bulanNama[$bulanFilter] ?> <?= $tahunFilter ?> |
                        <strong>Total Pendapatan:</strong> Rp <?= number_format($totalPendapatan, 0, ',', '.') ?>
                    </div>
                    <!-- Chart Section -->
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <div class="card card-outline card-success">
                                <div class="card-header">
                                    <h3 class="card-title"><i class="fas fa-chart-pie"></i> Status Pembayaran</h3>
                                </div>
                                <div class="card-body">
                                    <canvas id="chartStatus" height="200"></canvas>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="card card-outline card-primary">
                                <div class="card-header">
                                    <h3 class="card-title"><i class="fas fa-chart-bar"></i> Pendapatan per Kategori</h3>
                                </div>
                                <div class="card-body">
                                    <canvas id="chartKategori" height="100"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>No Rekening</th>
                                    <th>Pelanggan</th>
                                    <th>Kategori</th>
                                    <th>Pemakaian (m³)</th>
                                    <th>Total Tagihan</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (mysqli_num_rows($result) > 0): ?>
                                    <?php $no = 1; while ($row = mysqli_fetch_assoc($result)): ?>
                                    <tr>
                                        <td><?= $no++ ?></td>
                                        <td><?= $row['noRekening'] ?></td>
                                        <td><?= htmlspecialchars($row['namaPelanggan']) ?></td>
                                        <td><?= htmlspecialchars($row['namaKategori'] ?? '-') ?></td>
                                        <td><?= $row['pemakaian'] ?></td>
                                        <td>Rp <?= number_format($row['totalTagihan'], 0, ',', '.') ?></td>
                                        <td>
                                            <?php if ($row['statusBayar'] === 'Lunas'): ?>
                                                <span class="badge badge-success">Lunas</span>
                                            <?php else: ?>
                                                <span class="badge badge-danger">Belum Bayar</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <tr><td colspan="7" class="text-center">Tidak ada data untuk periode ini</td></tr>
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
    // Pie Chart - Status Pembayaran
    const statusData = <?= json_encode($chartStatus) ?>;
    const lunas = statusData['Lunas'] ? statusData['Lunas'].jumlah : 0;
    const belum = statusData['Belum Bayar'] ? statusData['Belum Bayar'].jumlah : 0;

    new Chart(document.getElementById('chartStatus'), {
        type: 'doughnut',
        data: {
            labels: ['Lunas', 'Belum Bayar'],
            datasets: [{
                data: [lunas, belum],
                backgroundColor: ['rgba(40, 167, 69, 0.8)', 'rgba(220, 53, 69, 0.8)'],
                borderColor: ['rgba(40, 167, 69, 1)', 'rgba(220, 53, 69, 1)'],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'bottom' }
            }
        }
    });

    // Bar Chart - Pendapatan per Kategori
    const kategoriLabels = <?= json_encode($chartKategoriLabels) ?>;
    const kategoriData = <?= json_encode($chartKategoriData) ?>;
    const colors = [
        'rgba(60, 141, 188, 0.7)', 'rgba(0, 166, 90, 0.7)', 'rgba(243, 156, 18, 0.7)',
        'rgba(221, 75, 57, 0.7)', 'rgba(0, 192, 239, 0.7)', 'rgba(96, 92, 168, 0.7)'
    ];

    new Chart(document.getElementById('chartKategori'), {
        type: 'bar',
        data: {
            labels: kategoriLabels,
            datasets: [{
                label: 'Pendapatan (Rp)',
                data: kategoriData,
                backgroundColor: colors.slice(0, kategoriLabels.length),
                borderWidth: 1
            }]
        },
        options: {
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
                y: {
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
