<?php
$pageTitle = "Dashboard - PDAM Zernih";
$activePage = "dashboard";
$baseUrl = "";
include 'assets/layouts/header.php';
include 'koneksi.php';

$totalPelanggan = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(id) as total FROM pelanggan"))['total'];
$totalTagihan = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(id) as total FROM tagihan"))['total'];
$totalKaryawan = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(id) as total FROM karyawan"))['total'];
$pendapatan = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COALESCE(SUM(totalTagihan), 0) as total FROM tagihan"))['total'];
?>

<?php include 'assets/layouts/sidebar.php'; ?>

    <!-- Content Header -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Dashboard</h1>
                </div>
            </div>
        </div>
    </div>

    <!-- Main content -->
    <section class="content pt-3">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3><?= $totalPelanggan ?></h3>
                            <p>Total Pelanggan</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-users"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3><?= $totalTagihan ?></h3>
                            <p>Total Tagihan</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-file-invoice-dollar"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h3><?= $totalKaryawan ?></h3>
                            <p>Total Karyawan</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-user-tie"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-primary">
                        <div class="inner">
                            <h3>Rp <?= number_format($pendapatan, 0, ',', '.') ?></h3>
                            <p>Total Pendapatan</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-money-bill-wave"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

</div>
<?php include 'assets/layouts/footer.php'; ?>
