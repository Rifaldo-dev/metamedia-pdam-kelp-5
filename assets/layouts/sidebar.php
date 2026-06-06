<!-- Main Sidebar -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <a href="<?= $baseUrl ?>dashboard.php" class="brand-link">
        <span class="brand-text font-weight-light"><b>PDAM</b> Zernih</span>
    </a>

    <div class="sidebar">
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <li class="nav-item">
                    <a href="<?= $baseUrl ?>dashboard.php" class="nav-link <?= ($activePage ?? '') === 'dashboard' ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>Dashboard</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?= $baseUrl ?>kategori/dataKategori.php" class="nav-link <?= ($activePage ?? '') === 'kategori' ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-tags"></i>
                        <p>Kategori</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?= $baseUrl ?>karyawan/dataKaryawan.php" class="nav-link <?= ($activePage ?? '') === 'karyawan' ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-user-tie"></i>
                        <p>Karyawan</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?= $baseUrl ?>pelanggan/dataPelanggan.php" class="nav-link <?= ($activePage ?? '') === 'pelanggan' ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-users"></i>
                        <p>Pelanggan</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?= $baseUrl ?>tagihan/dataTagihan.php" class="nav-link <?= ($activePage ?? '') === 'tagihan' ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-file-invoice-dollar"></i>
                        <p>Tagihan</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?= $baseUrl ?>laporan/pendapatanPerPeriode.php" class="nav-link <?= ($activePage ?? '') === 'laporan' ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-chart-bar"></i>
                        <p>Laporan</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?= $baseUrl ?>auth/logout.php" class="nav-link">
                        <i class="nav-icon fas fa-sign-out-alt"></i>
                        <p>Logout</p>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</aside>

<!-- Navbar -->
<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
    </ul>
    <ul class="navbar-nav ml-auto">
        <li class="nav-item">
            <span class="nav-link"><?= $_SESSION['namaKaryawan'] ?? $_SESSION['username'] ?></span>
        </li>
    </ul>
</nav>

<!-- Content Wrapper -->
<div class="content-wrapper">
