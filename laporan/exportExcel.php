<?php
session_start();
include '../koneksi.php';
include '../auth/cekLogin.php';

$tipe = isset($_GET['tipe']) ? $_GET['tipe'] : '';
$bulan = isset($_GET['bulan']) ? (int)$_GET['bulan'] : 0;
$tahun = isset($_GET['tahun']) ? (int)$_GET['tahun'] : (int)date('Y');
$limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;

$bulanNama = ['','Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];

// Set header untuk download Excel
$filename = "laporan_pendapatan_";
if ($tipe === 'periode') {
    $filename .= "periode_{$bulanNama[$bulan]}_{$tahun}";
} elseif ($tipe === 'tahun') {
    $filename .= "tahun_{$tahun}";
} elseif ($tipe === 'tertinggi') {
    $filename .= "tertinggi_{$tahun}";
}
$filename .= ".xls";

header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=\"$filename\"");
header("Pragma: no-cache");
header("Expires: 0");

echo '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel">';
echo '<head><meta charset="UTF-8"></head>';
echo '<body>';

if ($tipe === 'periode') {
    $query = "SELECT t.*, p.noRekening, p.namaPelanggan, k.namaKategori
              FROM tagihan t 
              JOIN pelanggan p ON t.pelangganId = p.id 
              LEFT JOIN kategori k ON p.kategoriId = k.id 
              WHERE t.periodeBulan = $bulan AND t.periodeTahun = $tahun 
              ORDER BY t.id DESC";
    $result = mysqli_query($conn, $query);

    echo "<h3>Laporan Pendapatan - {$bulanNama[$bulan]} $tahun</h3>";
    echo '<table border="1">';
    echo '<tr><th>No</th><th>No Rekening</th><th>Pelanggan</th><th>Kategori</th><th>Pemakaian (m³)</th><th>Total Tagihan</th><th>Status</th></tr>';
    
    $no = 1;
    $total = 0;
    while ($row = mysqli_fetch_assoc($result)) {
        echo '<tr>';
        echo '<td>' . $no++ . '</td>';
        echo '<td>' . $row['noRekening'] . '</td>';
        echo '<td>' . $row['namaPelanggan'] . '</td>';
        echo '<td>' . ($row['namaKategori'] ?? '-') . '</td>';
        echo '<td>' . $row['pemakaian'] . '</td>';
        echo '<td>' . $row['totalTagihan'] . '</td>';
        echo '<td>' . $row['statusBayar'] . '</td>';
        echo '</tr>';
        $total += $row['totalTagihan'];
    }
    echo '<tr><td colspan="5" style="text-align:right"><strong>TOTAL</strong></td><td><strong>' . $total . '</strong></td><td></td></tr>';
    echo '</table>';

} elseif ($tipe === 'tahun') {
    $query = "SELECT periodeBulan, COUNT(id) as jumlahTagihan, SUM(pemakaian) as totalPemakaian, SUM(totalTagihan) as totalPendapatan
              FROM tagihan 
              WHERE periodeTahun = $tahun 
              GROUP BY periodeBulan 
              ORDER BY periodeBulan ASC";
    $result = mysqli_query($conn, $query);

    echo "<h3>Laporan Pendapatan Tahun $tahun</h3>";
    echo '<table border="1">';
    echo '<tr><th>No</th><th>Bulan</th><th>Jumlah Tagihan</th><th>Total Pemakaian (m³)</th><th>Total Pendapatan</th></tr>';
    
    $no = 1;
    $total = 0;
    while ($row = mysqli_fetch_assoc($result)) {
        echo '<tr>';
        echo '<td>' . $no++ . '</td>';
        echo '<td>' . $bulanNama[$row['periodeBulan']] . '</td>';
        echo '<td>' . $row['jumlahTagihan'] . '</td>';
        echo '<td>' . $row['totalPemakaian'] . '</td>';
        echo '<td>' . $row['totalPendapatan'] . '</td>';
        echo '</tr>';
        $total += $row['totalPendapatan'];
    }
    echo '<tr><td colspan="4" style="text-align:right"><strong>TOTAL</strong></td><td><strong>' . $total . '</strong></td></tr>';
    echo '</table>';

} elseif ($tipe === 'tertinggi') {
    $query = "SELECT p.noRekening, p.namaPelanggan, k.namaKategori, 
                     COUNT(t.id) as jumlahBulan, 
                     SUM(t.pemakaian) as totalPemakaian, 
                     SUM(t.totalTagihan) as totalPendapatan
              FROM tagihan t 
              JOIN pelanggan p ON t.pelangganId = p.id 
              LEFT JOIN kategori k ON p.kategoriId = k.id 
              WHERE t.periodeTahun = $tahun 
              GROUP BY t.pelangganId 
              ORDER BY totalPendapatan DESC 
              LIMIT $limit";
    $result = mysqli_query($conn, $query);

    echo "<h3>Top $limit Pelanggan Pendapatan Tertinggi - Tahun $tahun</h3>";
    echo '<table border="1">';
    echo '<tr><th>Peringkat</th><th>No Rekening</th><th>Pelanggan</th><th>Kategori</th><th>Jumlah Bulan</th><th>Total Pemakaian (m³)</th><th>Total Pendapatan</th></tr>';
    
    $no = 1;
    while ($row = mysqli_fetch_assoc($result)) {
        echo '<tr>';
        echo '<td>' . $no++ . '</td>';
        echo '<td>' . $row['noRekening'] . '</td>';
        echo '<td>' . $row['namaPelanggan'] . '</td>';
        echo '<td>' . ($row['namaKategori'] ?? '-') . '</td>';
        echo '<td>' . $row['jumlahBulan'] . '</td>';
        echo '<td>' . $row['totalPemakaian'] . '</td>';
        echo '<td>' . $row['totalPendapatan'] . '</td>';
        echo '</tr>';
    }
    echo '</table>';
}

echo '</body></html>';
