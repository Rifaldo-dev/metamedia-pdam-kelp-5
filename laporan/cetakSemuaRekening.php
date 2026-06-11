<?php
session_start();
include '../koneksi.php';
include '../auth/cekLogin.php';

// Filter periode
$bulanFilter = isset($_GET['bulan']) ? (int)$_GET['bulan'] : (int)date('m');
$tahunFilter = isset($_GET['tahun']) ? (int)$_GET['tahun'] : (int)date('Y');

// Ambil data tagihan semua pelanggan sesuai periode
$query = "SELECT t.*, p.noRekening, p.namaPelanggan, k.namaKategori, kr.nik, kr.namaKaryawan
          FROM tagihan t 
          JOIN pelanggan p ON t.pelangganId = p.id 
          LEFT JOIN kategori k ON p.kategoriId = k.id 
          LEFT JOIN karyawan kr ON t.karyawanId = kr.id
          WHERE t.periodeBulan = $bulanFilter AND t.periodeTahun = $tahunFilter
          ORDER BY t.id ASC";
$result = mysqli_query($conn, $query);

// Total tagihan
$totalTagihan = mysqli_fetch_assoc(mysqli_query($conn, 
    "SELECT COALESCE(SUM(totalTagihan), 0) as total FROM tagihan WHERE periodeBulan = $bulanFilter AND periodeTahun = $tahunFilter"))['total'];

// Ambil data kasir (user yang login)
$userId = $_SESSION['user_id'] ?? 0;
$kasirQuery = mysqli_query($conn, "SELECT k.nik, k.namaKaryawan FROM users u JOIN karyawan k ON u.karyawanId = k.id WHERE u.id = $userId");
$kasir = mysqli_fetch_assoc($kasirQuery);

$bulanNama = ['','Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Pendapatan Rekening - <?= $bulanNama[$bulanFilter] ?> <?= $tahunFilter ?></title>
    <link rel="stylesheet" href="../assets/css/print.css">
    <style>
        body {
            font-family: 'Times New Roman', serif;
            font-size: 12pt;
            margin: 20px;
            color: #000;
        }
        .print-title {
            text-align: center;
            margin-bottom: 20px;
        }
        .print-title h3, .print-title h4, .print-title p {
            margin: 2px 0;
        }
        table.laporan {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        table.laporan th, table.laporan td {
            border: 1px solid #000;
            padding: 5px 8px;
            text-align: center;
            font-size: 10pt;
        }
        table.laporan th {
            background: #f0f0f0;
            font-weight: bold;
        }
        table.laporan td.text-left {
            text-align: left;
        }
        table.laporan td.text-right {
            text-align: right;
        }
        .footer-section {
            margin-top: 20px;
            text-align: right;
            font-size: 11pt;
        }
        .footer-section .signature {
            margin-top: 60px;
        }
        .no-print {
            margin-bottom: 15px;
        }
        @media print {
            .no-print { display: none !important; }
            body { margin: 10px; }
        }
        .btn {
            display: inline-block;
            padding: 6px 14px;
            font-size: 13px;
            text-decoration: none;
            border-radius: 4px;
            cursor: pointer;
            border: none;
        }
        .btn-primary { background: #007bff; color: #fff; }
        .btn-secondary { background: #6c757d; color: #fff; }
    </style>
</head>
<body>
    <div class="no-print">
        <button onclick="window.print()" class="btn btn-primary">&#128438; Cetak</button>
        <a href="cetakLaporan.php" class="btn btn-secondary">Kembali</a>
    </div>

    <div class="print-title">
        <h3>PERUSAHAAN DAERAH AIR ZERNIH</h3>
        <h4>LAPORAN PENDAPATAN REKENING</h4>
        <p><strong>PERIODE: <?= strtoupper($bulanNama[$bulanFilter]) ?>-<?= $tahunFilter ?></strong></p>
    </div>

    <table class="laporan">
        <thead>
            <tr>
                <th>No</th>
                <th>No Rek</th>
                <th>Tgl Tagih</th>
                <th>Nama</th>
                <th>Kategori</th>
                <th>HPKA<br>Rp.</th>
                <th>Adm<br>Rp</th>
                <th>MBL<br>M³</th>
                <th>MBI<br>M³</th>
                <th>Pemakaian<br>M³</th>
                <th>Tagihan Rp.</th>
            </tr>
        </thead>
        <tbody>
            <?php if (mysqli_num_rows($result) > 0): ?>
                <?php $no = 1; while ($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><?= htmlspecialchars($row['noRekening']) ?></td>
                    <td><?= date('d-M-Y', strtotime($row['tglTagih'])) ?></td>
                    <td class="text-left"><?= htmlspecialchars($row['namaPelanggan']) ?></td>
                    <td><?= htmlspecialchars($row['namaKategori'] ?? '-') ?></td>
                    <td class="text-right"><?= number_format($row['hargaPerKubikAir'], 0, ',', '.') ?></td>
                    <td class="text-right"><?= number_format($row['biayaAdm'], 0, ',', '.') ?></td>
                    <td class="text-right"><?= number_format($row['meterBulanLalu'], 0, ',', '.') ?></td>
                    <td class="text-right"><?= number_format($row['meterBulanIni'], 0, ',', '.') ?></td>
                    <td class="text-right"><?= number_format($row['pemakaian'], 0, ',', '.') ?></td>
                    <td class="text-right"><?= number_format($row['totalTagihan'], 0, ',', '.') ?></td>
                </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr><td colspan="11">Tidak ada data untuk periode ini</td></tr>
            <?php endif; ?>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="10" style="text-align: right; border: 1px solid #000;"><strong>Total Tagihan Rp.</strong></td>
                <td class="text-right" style="border: 1px solid #000;"><strong><?= number_format($totalTagihan, 0, ',', '.') ?></strong></td>
            </tr>
        </tfoot>
    </table>

    <div class="footer-section">
        <p>Padang, <?= date('d/m/Y') ?></p>
        <p>Kasir</p>
        <div class="signature">
            <p><strong>NIK: <?= $kasir['nik'] ?? '-' ?>/ <?= $kasir['namaKaryawan'] ?? '-' ?></strong></p>
        </div>
    </div>
</body>
</html>
