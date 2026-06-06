<?php
$pageTitle = "Edit Kategori - PDAM Zernih";
$activePage = "kategori";
$baseUrl = "../";
include '../assets/layouts/header.php';
include '../koneksi.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM kategori WHERE id = $id"));

if (!$data) {
    $_SESSION['error'] = "Data kategori tidak ditemukan!";
    header("Location: dataKategori.php");
    exit;
}
?>

<?php include '../assets/layouts/sidebar.php'; ?>

    <!-- Content Header -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Edit Kategori</h1>
                </div>
            </div>
        </div>
    </div>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <form action="updateKategori.php" method="POST">
                    <input type="hidden" name="id" value="<?= $data['id'] ?>">
                    <div class="card-body">
                        <div class="form-group">
                            <label>Kode Kategori</label>
                            <input type="text" name="kodeKategori" class="form-control" maxlength="5" value="<?= htmlspecialchars($data['kodeKategori']) ?>" required>
                        </div>
                        <div class="form-group">
                            <label>Nama Kategori</label>
                            <input type="text" name="namaKategori" class="form-control" maxlength="50" value="<?= htmlspecialchars($data['namaKategori']) ?>" required>
                        </div>
                        <div class="form-group">
                            <label>Biaya Administrasi</label>
                            <input type="number" name="biayaAdm" class="form-control" min="0" value="<?= $data['biayaAdm'] ?>" required>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Update</button>
                        <a href="dataKategori.php" class="btn btn-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </section>

</div>
<?php include '../assets/layouts/footer.php'; ?>
