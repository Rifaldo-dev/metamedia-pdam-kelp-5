<?php
$pageTitle = "Edit Karyawan - PDAM Zernih";
$activePage = "karyawan";
$baseUrl = "../";
include '../assets/layouts/header.php';
include '../koneksi.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM karyawan WHERE id = $id"));

if (!$data) {
    $_SESSION['error'] = "Data karyawan tidak ditemukan!";
    header("Location: dataKaryawan.php");
    exit;
}
?>

<?php include '../assets/layouts/sidebar.php'; ?>

    <!-- Content Header -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Edit Karyawan</h1>
                </div>
            </div>
        </div>
    </div>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <form action="updateKaryawan.php" method="POST">
                    <input type="hidden" name="id" value="<?= $data['id'] ?>">
                    <div class="card-body">
                        <div class="form-group">
                            <label>NIK</label>
                            <input type="text" name="nik" class="form-control" maxlength="20" value="<?= htmlspecialchars($data['nik']) ?>" required>
                        </div>
                        <div class="form-group">
                            <label>Nama Karyawan</label>
                            <input type="text" name="namaKaryawan" class="form-control" maxlength="100" value="<?= htmlspecialchars($data['namaKaryawan']) ?>" required>
                        </div>
                        <div class="form-group">
                            <label>Jabatan</label>
                            <input type="text" name="jabatan" class="form-control" maxlength="50" value="<?= htmlspecialchars($data['jabatan']) ?>" required>
                        </div>
                        <div class="form-group">
                            <label>No HP</label>
                            <input type="text" name="noHp" class="form-control" maxlength="20" value="<?= htmlspecialchars($data['noHp']) ?>">
                        </div>
                        <div class="form-group">
                            <label>Alamat</label>
                            <textarea name="alamat" class="form-control" rows="3"><?= htmlspecialchars($data['alamat']) ?></textarea>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Update</button>
                        <a href="dataKaryawan.php" class="btn btn-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </section>

</div>
<?php include '../assets/layouts/footer.php'; ?>
