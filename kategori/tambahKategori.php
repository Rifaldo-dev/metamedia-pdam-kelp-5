<?php
$pageTitle = "Tambah Kategori - PDAM Zernih";
$activePage = "kategori";
$baseUrl = "../";
include '../assets/layouts/header.php';
?>

<?php include '../assets/layouts/sidebar.php'; ?>

    <!-- Content Header -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Tambah Kategori</h1>
                </div>
            </div>
        </div>
    </div>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <form action="simpanKategori.php" method="POST">
                    <div class="card-body">
                        <div class="form-group">
                            <label>Kode Kategori</label>
                            <input type="text" name="kodeKategori" class="form-control" maxlength="5" placeholder="Contoh: K01" required>
                        </div>
                        <div class="form-group">
                            <label>Nama Kategori</label>
                            <input type="text" name="namaKategori" class="form-control" maxlength="50" placeholder="Contoh: Rumah Tangga" required>
                        </div>
                        <div class="form-group">
                            <label>Biaya Administrasi</label>
                            <input type="number" name="biayaAdm" class="form-control" min="0" placeholder="Contoh: 5000" required>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan</button>
                        <a href="dataKategori.php" class="btn btn-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </section>

</div>
<?php include '../assets/layouts/footer.php'; ?>
