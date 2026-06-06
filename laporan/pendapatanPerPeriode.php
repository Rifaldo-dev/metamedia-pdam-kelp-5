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
<?php include '../assets/layouts/footer.php'; ?>
