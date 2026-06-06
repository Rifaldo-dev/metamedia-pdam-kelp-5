<?php
$pageTitle = "Edit Pelanggan - PDAM Zernih";
$activePage = "pelanggan";
$baseUrl = "../";
include '../assets/layouts/header.php';
include '../koneksi.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM pelanggan WHERE id = $id"));

if (!$data) {
    $_SESSION['error'] = "Data pelanggan tidak ditemukan!";
    header("Location: dataPelanggan.php");
    exit;
}

// Ambil data kategori untuk dropdown
$kategoriList = mysqli_query($conn, "SELECT id, namaKategori FROM kategori ORDER BY namaKategori ASC");
?>

<?php include '../assets/layouts/sidebar.php'; ?>

    <!-- Content Header -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Edit Pelanggan</h1>
                </div>
            </div>
        </div>
    </div>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <form action="updatePelanggan.php" method="POST">
                    <input type="hidden" name="id" value="<?= $data['id'] ?>">
                    <div class="card-body">
                        <div class="form-group">
                            <label>No Rekening</label>
                            <input type="text" name="noRekening" class="form-control" maxlength="20" value="<?= htmlspecialchars($data['noRekening']) ?>" required>
                        </div>
                        <div class="form-group">
                            <label>Nama Pelanggan</label>
                            <input type="text" name="namaPelanggan" class="form-control" maxlength="100" value="<?= htmlspecialchars($data['namaPelanggan']) ?>" required>
                        </div>
                        <div class="form-group">
                            <label>Alamat</label>
                            <textarea name="alamat" class="form-control" rows="3" required><?= htmlspecialchars($data['alamat']) ?></textarea>
                        </div>
                        <div class="form-group">
                            <label>No HP</label>
                            <input type="text" name="noHp" class="form-control" maxlength="20" value="<?= htmlspecialchars($data['noHp']) ?>">
                        </div>
                        <div class="form-group">
                            <label>Kategori</label>
                            <select name="kategoriId" class="form-control" required>
                                <option value="">-- Pilih Kategori --</option>
                                <?php while ($kat = mysqli_fetch_assoc($kategoriList)): ?>
                                    <option value="<?= $kat['id'] ?>" <?= $kat['id'] == $data['kategoriId'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($kat['namaKategori']) ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Update</button>
                        <a href="dataPelanggan.php" class="btn btn-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </section>

</div>
<?php include '../assets/layouts/footer.php'; ?>
