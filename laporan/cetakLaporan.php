<?php
$pageTitle = "Cetak Rekening - PDAM Zernih";
$activePage = "laporan";
$activeSubPage = "cetak";
$baseUrl = "../";
include '../assets/layouts/header.php';
include '../koneksi.php';

// Ambil data pelanggan
$pelangganList = mysqli_query($conn, "SELECT id, noRekening, namaPelanggan FROM pelanggan ORDER BY namaPelanggan ASC");
?>

<?php include '../assets/layouts/sidebar.php'; ?>

    <!-- Content Header -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Cetak Rekening</h1>
                </div>
            </div>
        </div>
    </div>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-body">
                    <form action="cetakRekening.php" method="GET" target="_blank">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Pelanggan</label>
                                    <select name="pelangganId" class="form-control" required>
                                        <option value="">-- Pilih Pelanggan --</option>
                                        <?php while ($pel = mysqli_fetch_assoc($pelangganList)): ?>
                                            <option value="<?= $pel['id'] ?>">[<?= $pel['noRekening'] ?>] <?= htmlspecialchars($pel['namaPelanggan']) ?></option>
                                        <?php endwhile; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Bulan</label>
                                    <select name="periodeBulan" class="form-control">
                                        <option value="0">-- Semua --</option>
                                        <?php 
                                        $bulanNama = ['','Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
                                        for ($i = 1; $i <= 12; $i++): ?>
                                            <option value="<?= $i ?>"><?= $bulanNama[$i] ?></option>
                                        <?php endfor; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Tahun</label>
                                    <select name="periodeTahun" class="form-control">
                                        <option value="0">-- Semua --</option>
                                        <?php for ($y = date('Y'); $y >= 2020; $y--): ?>
                                            <option value="<?= $y ?>"><?= $y ?></option>
                                        <?php endfor; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>&nbsp;</label>
                                    <button type="submit" class="btn btn-primary btn-block">
                                        <i class="fas fa-print"></i> Cetak
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

</div>
<?php include '../assets/layouts/footer.php'; ?>
