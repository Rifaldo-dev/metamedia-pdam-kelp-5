<?php
$pageTitle = "Edit Tagihan - PDAM Zernih";
$activePage = "tagihan";
$baseUrl = "../";
include '../assets/layouts/header.php';
include '../koneksi.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$data = mysqli_fetch_assoc(mysqli_query($conn, 
    "SELECT t.*, p.noRekening, p.namaPelanggan 
     FROM tagihan t JOIN pelanggan p ON t.pelangganId = p.id 
     WHERE t.id = $id"));

if (!$data) {
    $_SESSION['error'] = "Data tagihan tidak ditemukan!";
    header("Location: dataTagihan.php");
    exit;
}
?>

<?php include '../assets/layouts/sidebar.php'; ?>

    <!-- Content Header -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Edit Tagihan</h1>
                </div>
            </div>
        </div>
    </div>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <form action="updateTagihan.php" method="POST" id="formTagihan">
                    <input type="hidden" name="id" value="<?= $data['id'] ?>">
                    <input type="hidden" name="pelangganId" value="<?= $data['pelangganId'] ?>">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Pelanggan</label>
                                    <input type="text" class="form-control bg-light" value="[<?= $data['noRekening'] ?>] <?= $data['namaPelanggan'] ?>" readonly>
                                </div>
                                <div class="form-group">
                                    <label>Periode Bulan</label>
                                    <select name="periodeBulan" class="form-control" required>
                                        <?php 
                                        $bulanNama = ['','Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
                                        for ($i = 1; $i <= 12; $i++): ?>
                                            <option value="<?= $i ?>" <?= $i == $data['periodeBulan'] ? 'selected' : '' ?>><?= $bulanNama[$i] ?></option>
                                        <?php endfor; ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Periode Tahun</label>
                                    <input type="number" name="periodeTahun" class="form-control" value="<?= $data['periodeTahun'] ?>" min="2020" max="2030" required>
                                </div>
                                <div class="form-group">
                                    <label>Tanggal Tagih</label>
                                    <input type="date" name="tglTagih" class="form-control" value="<?= $data['tglTagih'] ?>" required>
                                </div>
                                <div class="form-group">
                                    <label>Status Bayar</label>
                                    <select name="statusBayar" class="form-control" required>
                                        <option value="Belum Bayar" <?= $data['statusBayar'] == 'Belum Bayar' ? 'selected' : '' ?>>Belum Bayar</option>
                                        <option value="Lunas" <?= $data['statusBayar'] == 'Lunas' ? 'selected' : '' ?>>Lunas</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Meter Bulan Lalu (m³)</label>
                                    <input type="number" name="meterBulanLalu" id="meterBulanLalu" class="form-control" min="0" value="<?= $data['meterBulanLalu'] ?>" required>
                                </div>
                                <div class="form-group">
                                    <label>Meter Bulan Ini (m³)</label>
                                    <input type="number" name="meterBulanIni" id="meterBulanIni" class="form-control" min="0" value="<?= $data['meterBulanIni'] ?>" required>
                                </div>
                                <hr>
                                <div class="form-group">
                                    <label>Pemakaian (m³)</label>
                                    <input type="text" id="pemakaian" class="form-control bg-light" value="<?= $data['pemakaian'] ?> m³" readonly>
                                </div>
                                <div class="form-group">
                                    <label>Harga per m³</label>
                                    <input type="text" id="hargaPerKubik" class="form-control bg-light" value="Rp <?= number_format($data['hargaPerKubikAir'], 0, ',', '.') ?>" readonly>
                                </div>
                                <div class="form-group">
                                    <label>Biaya Administrasi</label>
                                    <input type="text" id="biayaAdm" class="form-control bg-light" value="Rp <?= number_format($data['biayaAdm'], 0, ',', '.') ?>" readonly>
                                </div>
                                <div class="form-group">
                                    <label><strong>Total Tagihan</strong></label>
                                    <input type="text" id="totalTagihan" class="form-control bg-warning font-weight-bold" value="Rp <?= number_format($data['totalTagihan'], 0, ',', '.') ?>" readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Update</button>
                        <a href="dataTagihan.php" class="btn btn-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </section>

</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const pelangganId = document.querySelector('input[name="pelangganId"]').value;
    const meterLalu = document.getElementById('meterBulanLalu');
    const meterIni = document.getElementById('meterBulanIni');

    meterLalu.addEventListener('input', hitungTagihan);
    meterIni.addEventListener('input', hitungTagihan);

    function hitungTagihan() {
        const ml = parseInt(meterLalu.value) || 0;
        const mi = parseInt(meterIni.value) || 0;

        const formData = new FormData();
        formData.append('pelangganId', pelangganId);
        formData.append('meterBulanLalu', ml);
        formData.append('meterBulanIni', mi);

        fetch('../ajax/hitungTagihan.php', { method: 'POST', body: formData })
            .then(r => r.json())
            .then(data => {
                if (!data.error) {
                    document.getElementById('pemakaian').value = data.pemakaian + ' m³';
                    document.getElementById('hargaPerKubik').value = 'Rp ' + Number(data.hargaPerKubikAir).toLocaleString('id-ID');
                    document.getElementById('biayaAdm').value = 'Rp ' + Number(data.biayaAdm).toLocaleString('id-ID');
                    document.getElementById('totalTagihan').value = 'Rp ' + Number(data.totalTagihan).toLocaleString('id-ID');
                } else {
                    document.getElementById('pemakaian').value = data.error;
                }
            });
    }
});
</script>

<?php include '../assets/layouts/footer.php'; ?>
