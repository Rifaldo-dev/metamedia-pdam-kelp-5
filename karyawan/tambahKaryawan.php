<?php
$pageTitle = "Tambah Karyawan - PDAM Zernih";
$activePage = "karyawan";
$baseUrl = "../";
include '../assets/layouts/header.php';
?>

<?php include '../assets/layouts/sidebar.php'; ?>

    <!-- Content Header -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Tambah Karyawan</h1>
                </div>
            </div>
        </div>
    </div>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <form action="simpanKaryawan.php" method="POST">
                    <div class="card-body">
                        <div class="form-group">
                            <label>NIK</label>
                            <input type="text" name="nik" class="form-control" maxlength="20" placeholder="Nomor Induk Karyawan" required>
                        </div>
                        <div class="form-group">
                            <label>Nama Karyawan</label>
                            <input type="text" name="namaKaryawan" class="form-control" maxlength="100" placeholder="Nama lengkap" required>
                        </div>
                        <div class="form-group">
                            <label>Jabatan</label>
                            <input type="text" name="jabatan" class="form-control" maxlength="50" placeholder="Contoh: Petugas Lapangan" required>
                        </div>
                        <div class="form-group">
                            <label>No HP</label>
                            <input type="text" name="noHp" class="form-control" maxlength="20" placeholder="08xxxxxxxxxx">
                        </div>
                        <div class="form-group">
                            <label>Alamat</label>
                            <textarea name="alamat" class="form-control" rows="3" placeholder="Alamat lengkap"></textarea>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan</button>
                        <a href="dataKaryawan.php" class="btn btn-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </section>

</div>
<?php include '../assets/layouts/footer.php'; ?>
