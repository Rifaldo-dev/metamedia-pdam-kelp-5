<?php
session_start();
include '../koneksi.php';
include '../auth/cekLogin.php';

$pelangganId = isset($_GET['pelangganId']) ? (int)$_GET['pelangganId'] : 0;
$periodeBulan = isset($_GET['periodeBulan']) ? (int)$_GET['periodeBulan'] : 0;
$periodeTahun = isset($_GET['periodeTahun']) ? (int)$_GET['periodeTahun'] : 0;

// Ambil data tagihan
$where = "WHERE t.pelangganId = $pelangganId";
if ($periodeBulan > 0) $where .= " AND t.periodeBulan = $periodeBulan";
if ($periodeTahun > 0) $where .= " AND t.periodeTahun = $periodeTahun";

$query = "SELECT t.*, p.noRekening, p.namaPelanggan, p.alamat, p.noHp, k.namaKategori
          FROM tagihan t 
          JOIN pelanggan p ON t.pelangganId = p.id 
          LEFT JOIN kategori k ON p.kategoriId = k.id 
          $where 
          ORDER BY t.periodeTahun DESC, t.periodeBulan DESC";
$result = mysqli_query($conn, $query);
$firstRow = mysqli_fetch_assoc($result);

if (!$firstRow) {
    echo "<script>alert('Data tagihan tidak ditemukan!'); history.back();</script>";
    exit;
}

$bulanNama = ['','Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Rekening Air - <?= $firstRow['namaPelanggan'] ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/css/print.css">
</head>
<body>
    <div class="container mt-4">
        <div class="no-print mb-3">
            <button onclick="window.print()" class="btn btn-primary"><i class="fas fa-print"></i> Cetak</button>
            <a href="cetakLaporan.php" class="btn btn-secondary">Kembali</a>
        </div>

        <div class="rekening-box">
            <div class="print-header">
                <h2>PDAM ZERNIH</h2>
                <p>Jl. Air Bersih No. 1, Kota Zernih</p>
                <p>Telp: (021) 123-4567</p>
            </div>

            <h5 class="text-center mb-3"><strong>REKENING AIR</strong></h5>

            <table class="rekening-detail mb-3">
                <tr>
                    <td width="150">No Rekening</td>
                    <td width="10">:</td>
                    <td><strong><?= $firstRow['noRekening'] ?></strong></td>
                </tr>
                <tr>
                    <td>Nama Pelanggan</td>
                    <td>:</td>
                    <td><?= htmlspecialchars($firstRow['namaPelanggan']) ?></td>
                </tr>
                <tr>
                    <td>Alamat</td>
                    <td>:</td>
                    <td><?= htmlspecialchars($firstRow['alamat']) ?></td>
                </tr>
                <tr>
                    <td>Kategori</td>
                    <td>:</td>
                    <td><?= htmlspecialchars($firstRow['namaKategori'] ?? '-') ?></td>
                </tr>
                <tr>
                    <td>Periode</td>
                    <td>:</td>
                    <td><?= $bulanNama[$firstRow['periodeBulan']] ?> <?= $firstRow['periodeTahun'] ?></td>
                </tr>
            </table>

            <table class="table table-bordered">
                <tr>
                    <td width="200">Meter Bulan Lalu</td>
                    <td><?= $firstRow['meterBulanLalu'] ?> m³</td>
                </tr>
                <tr>
                    <td>Meter Bulan Ini</td>
                    <td><?= $firstRow['meterBulanIni'] ?> m³</td>
                </tr>
                <tr>
                    <td>Pemakaian</td>
                    <td><?= $firstRow['pemakaian'] ?> m³</td>
                </tr>
                <tr>
                    <td>Harga per m³</td>
                    <td>Rp <?= number_format($firstRow['hargaPerKubikAir'], 0, ',', '.') ?></td>
                </tr>
                <tr>
                    <td>Biaya Pemakaian</td>
                    <td>Rp <?= number_format($firstRow['pemakaian'] * $firstRow['hargaPerKubikAir'], 0, ',', '.') ?></td>
                </tr>
                <tr>
                    <td>Biaya Administrasi</td>
                    <td>Rp <?= number_format($firstRow['biayaAdm'], 0, ',', '.') ?></td>
                </tr>
                <tr class="total-row">
                    <td><strong>TOTAL TAGIHAN</strong></td>
                    <td><strong>Rp <?= number_format($firstRow['totalTagihan'], 0, ',', '.') ?></strong></td>
                </tr>
            </table>

            <table class="rekening-detail">
                <tr>
                    <td>Status</td>
                    <td>:</td>
                    <td><strong><?= $firstRow['statusBayar'] ?></strong></td>
                </tr>
                <tr>
                    <td>Tanggal Tagih</td>
                    <td>:</td>
                    <td><?= date('d/m/Y', strtotime($firstRow['tglTagih'])) ?></td>
                </tr>
            </table>
        </div>
    </div>
</body>
</html>
