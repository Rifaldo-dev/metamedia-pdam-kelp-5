<?php
$pageTitle = "Laporan Pendapatan per Tahun - PDAM Zernih";
$activePage = "laporan";
$baseUrl = "../";
include '../assets/layouts/header.php';
include '../koneksi.php';

$tahunFilter = isset($_GET['tahun']) ? (int)$_GET['tahun'] : (int)date('Y');

// Query pendapatan per bulan dalam tahun
$query = "SELECT periodeBulan, COUNT(id) as jumlahTagihan, SUM(pemakaian) as totalPemakaian, SUM(totalTagihan) as totalPendapatan
          FROM tagihan 
          WHERE periodeTahun = $tahunFilter 
          GROUP BY periodeBulan 
          ORDER BY periodeBulan ASC";
$result = mysqli_query($conn, $query);

// Total tahunan
$totalTahunan = mysqli_fetch_assoc(mysqli_query($conn, 
    "SELECT COALESCE(SUM(totalTagihan), 0) as total FROM tagihan WHERE periodeTahun = $tahunFilter"))['total'];

$bulanNama = ['','Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
?>

<?php include '../assets/layouts/sidebar.php'; ?>

    <!-- Content Header -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Laporan Pendapatan per Tahun</h1>
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
                        <button type="submit" class="btn btn-primary btn-sm mr-2"><i class="fas fa-filter"></i> Filter</button>
                        <a href="exportExcel.php?tipe=tahun&tahun=<?= $tahunFilter ?>" class="btn btn-success btn-sm">
                            <i class="fas fa-file-excel"></i> Export Excel
                        </a>
                    </form>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <strong>Tahun:</strong> <?= $tahunFilter ?> |
                        <strong>Total Pendapatan:</strong> Rp <?= number_format($totalTahunan, 0, ',', '.') ?>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Bulan</th>
                                    <th>Jumlah Tagihan</th>
                                    <th>Total Pemakaian (m³)</th>
                                    <th>Total Pendapatan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (mysqli_num_rows($result) > 0): ?>
                                    <?php $no = 1; while ($row = mysqli_fetch_assoc($result)): ?>
                                    <tr>
                                        <td><?= $no++ ?></td>
                                        <td><?= $bulanNama[$row['periodeBulan']] ?></td>
                                        <td><?= $row['jumlahTagihan'] ?></td>
                                        <td><?= $row['totalPemakaian'] ?></td>
                                        <td>Rp <?= number_format($row['totalPendapatan'], 0, ',', '.') ?></td>
                                    </tr>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <tr><td colspan="5" class="text-center">Tidak ada data untuk tahun ini</td></tr>
                                <?php endif; ?>
                            </tbody>
                            <tfoot>
                                <tr class="font-weight-bold bg-light">
                                    <td colspan="4" class="text-right">TOTAL</td>
                                    <td>Rp <?= number_format($totalTahunan, 0, ',', '.') ?></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>

</div>
<?php include '../assets/layouts/footer.php'; ?>
