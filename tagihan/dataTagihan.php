<?php
$pageTitle = "Data Tagihan - PDAM Zernih";
$activePage = "tagihan";
$baseUrl = "../";
include '../assets/layouts/header.php';
include '../koneksi.php';

// Pagination
$limit = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Search
$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';
$where = '';
if ($search) {
    $where = "WHERE p.noRekening LIKE '%$search%' OR p.namaPelanggan LIKE '%$search%'";
}

// Count total
$totalData = mysqli_fetch_assoc(mysqli_query($conn, 
    "SELECT COUNT(t.id) as total FROM tagihan t JOIN pelanggan p ON t.pelangganId = p.id $where"))['total'];
$totalPages = ceil($totalData / $limit);

// Get data
$query = "SELECT t.*, p.noRekening, p.namaPelanggan, kr.namaKaryawan 
          FROM tagihan t 
          JOIN pelanggan p ON t.pelangganId = p.id 
          LEFT JOIN karyawan kr ON t.karyawanId = kr.id 
          $where 
          ORDER BY t.id DESC 
          LIMIT $limit OFFSET $offset";
$result = mysqli_query($conn, $query);
?>

<?php include '../assets/layouts/sidebar.php'; ?>

    <!-- Content Header -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Data Tagihan</h1>
                </div>
            </div>
        </div>
    </div>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">

            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success alert-dismissible fade show">
                    <?= $_SESSION['success']; unset($_SESSION['success']); ?>
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                </div>
            <?php endif; ?>

            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger alert-dismissible fade show">
                    <?= $_SESSION['error']; unset($_SESSION['error']); ?>
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                </div>
            <?php endif; ?>

            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <a href="tambahTagihan.php" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i> Input Tagihan
                        </a>
                        <div class="form-inline">
                            <div class="input-group input-group-sm">
                                <input type="text" id="liveSearch" class="form-control" placeholder="Cari no rekening / nama..." value="<?= htmlspecialchars($search) ?>" data-type="tagihan">
                                <div class="input-group-append">
                                    <span class="btn btn-default"><i class="fas fa-search"></i></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body table-responsive p-0">
                    <table class="table table-hover text-nowrap">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>No Rekening</th>
                                <th>Pelanggan</th>
                                <th>Periode</th>
                                <th>Pemakaian</th>
                                <th>Total Tagihan</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="tableBody">
                            <?php if (mysqli_num_rows($result) > 0): ?>
                                <?php $no = $offset + 1; while ($row = mysqli_fetch_assoc($result)): ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td><?= htmlspecialchars($row['noRekening']) ?></td>
                                    <td><?= htmlspecialchars($row['namaPelanggan']) ?></td>
                                    <td><?= $row['periodeBulan'] ?>/<?= $row['periodeTahun'] ?></td>
                                    <td><?= $row['pemakaian'] ?> m³</td>
                                    <td>Rp <?= number_format($row['totalTagihan'], 0, ',', '.') ?></td>
                                    <td>
                                        <?php if ($row['statusBayar'] === 'Lunas'): ?>
                                            <span class="badge badge-success">Lunas</span>
                                        <?php else: ?>
                                            <span class="badge badge-danger">Belum Bayar</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <a href="editTagihan.php?id=<?= $row['id'] ?>" class="btn btn-warning btn-sm">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="hapusTagihan.php?id=<?= $row['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin hapus data ini?')">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="8" class="text-center">Data tidak ditemukan</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <?php if ($totalPages > 1): ?>
                <div class="card-footer clearfix" id="paginationWrapper">
                    <ul class="pagination pagination-sm m-0 float-right" id="pagination">
                        <li class="page-item <?= $page <= 1 ? 'disabled' : '' ?>">
                            <a class="page-link" href="?page=<?= $page - 1 ?>&search=<?= urlencode($search) ?>">&laquo;</a>
                        </li>
                        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                            <a class="page-link" href="?page=<?= $i ?>&search=<?= urlencode($search) ?>"><?= $i ?></a>
                        </li>
                        <?php endfor; ?>
                        <li class="page-item <?= $page >= $totalPages ? 'disabled' : '' ?>">
                            <a class="page-link" href="?page=<?= $page + 1 ?>&search=<?= urlencode($search) ?>">&raquo;</a>
                        </li>
                    </ul>
                </div>
                <?php endif; ?>
            </div>

        </div>
    </section>

</div>
<?php include '../assets/layouts/footer.php'; ?>
