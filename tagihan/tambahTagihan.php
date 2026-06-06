<?php
$pageTitle = "Input Tagihan - PDAM Zernih";
$activePage = "tagihan";
$baseUrl = "../";
include '../assets/layouts/header.php';
include '../koneksi.php';

// Ambil data pelanggan untuk dropdown
$pelangganList = mysqli_query($conn, "SELECT p.id, p.noRekening, p.namaPelanggan FROM pelanggan p ORDER BY p.namaPelanggan ASC");

// Ambil karyawan yang login
$karyawanId = $_SESSION['karyawanId'] ?? 0;
?>

<?php include '../assets/layouts/sidebar.php'; ?>

    <!-- Content Header -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Input Tagihan</h1>
                </div>
            </div>
        </div>
    </div>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <form action="simpanTagihan.php" method="POST" id="formTagihan">
                    <input type="hidden" name="karyawanId" value="<?= $karyawanId ?>">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Pelanggan</label>
                                    <select name="pelangganId" id="pelangganId" class="form-control" required>
                                        <option value="">-- Pilih Pelanggan --</option>
                                        <?php while ($pel = mysqli_fetch_assoc($pelangganList)): ?>
                                            <option value="<?= $pel['id'] ?>">[<?= $pel['noRekening'] ?>] <?= htmlspecialchars($pel['namaPelanggan']) ?></option>
                                        <?php endwhile; ?>
                                    </select>
                                </div>
                                <div id="infoPelanggan" class="alert alert-info d-none">
                                    <small>
                                        <strong>Kategori:</strong> <span id="infoKategori">-</span><br>
                                        <strong>Alamat:</strong> <span id="infoAlamat">-</span>
                                    </small>
                                </div>
                                <div class="form-group">
                                    <label>Periode Bulan</label>
                                    <select name="periodeBulan" class="form-control" required>
                                        <?php 
                                        $bulanNama = ['','Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
                                        $bulanSekarang = (int)date('m');
                                        for ($i = 1; $i <= 12; $i++): ?>
                                            <option value="<?= $i ?>" <?= $i == $bulanSekarang ? 'selected' : '' ?>><?= $bulanNama[$i] ?></option>
                                        <?php endfor; ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Periode Tahun</label>
                                    <input type="number" name="periodeTahun" class="form-control" value="<?= date('Y') ?>" min="2020" max="2030" required>
                                </div>
                                <div class="form-group">
                                    <label>Tanggal Tagih</label>
                                    <input type="date" name="tglTagih" class="form-control" value="<?= date('Y-m-d') ?>" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Meter Bulan Lalu (m³)</label>
                                    <input type="number" name="meterBulanLalu" id="meterBulanLalu" class="form-control" min="0" value="0" required>
                                </div>
                                <div class="form-group">
                                    <label>Meter Bulan Ini (m³)</label>
                                    <input type="number" name="meterBulanIni" id="meterBulanIni" class="form-control" min="0" value="0" required>
                                </div>
                                <hr>
                                <div class="form-group">
                                    <label>Pemakaian (m³)</label>
                                    <input type="text" id="pemakaian" class="form-control bg-light" readonly>
                                </div>
                                <div class="form-group">
                                    <label>Harga per m³</label>
                                    <input type="text" id="hargaPerKubik" class="form-control bg-light" readonly>
                                </div>
                                <div class="form-group">
                                    <label>Biaya Administrasi</label>
                                    <input type="text" id="biayaAdm" class="form-control bg-light" readonly>
                                </div>
                                <div class="form-group">
                                    <label><strong>Total Tagihan</strong></label>
                                    <input type="text" id="totalTagihan" class="form-control bg-warning font-weight-bold" readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan Tagihan</button>
                        <a href="dataTagihan.php" class="btn btn-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </section>

</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const pelangganId = document.getElementById('pelangganId');
    const meterLalu = document.getElementById('meterBulanLalu');
    const meterIni = document.getElementById('meterBulanIni');

    // Saat pelanggan dipilih, tampilkan info
    pelangganId.addEventListener('change', function() {
        if (this.value) {
            fetch('../ajax/getDataPelanggan.php?id=' + this.value)
                .then(r => r.json())
                .then(data => {
                    if (!data.error) {
                        document.getElementById('infoKategori').textContent = data.namaKategori || '-';
                        document.getElementById('infoAlamat').textContent = data.alamat || '-';
                        document.getElementById('infoPelanggan').classList.remove('d-none');
                        hitungTagihan();
                    }
                });
        } else {
            document.getElementById('infoPelanggan').classList.add('d-none');
        }
    });

    // Hitung otomatis saat meter berubah
    meterLalu.addEventListener('input', hitungTagihan);
    meterIni.addEventListener('input', hitungTagihan);

    function hitungTagihan() {
        const pid = pelangganId.value;
        const ml = parseInt(meterLalu.value) || 0;
        const mi = parseInt(meterIni.value) || 0;

        if (!pid) return;

        const formData = new FormData();
        formData.append('pelangganId', pid);
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
                    document.getElementById('totalTagihan').value = '';
                }
            });
    }
});
</script>

<?php include '../assets/layouts/footer.php'; ?>
