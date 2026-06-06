<?php
include '../koneksi.php';

header('Content-Type: application/json');

$pelangganId = isset($_POST['pelangganId']) ? (int)$_POST['pelangganId'] : 0;
$meterBulanLalu = isset($_POST['meterBulanLalu']) ? (int)$_POST['meterBulanLalu'] : 0;
$meterBulanIni = isset($_POST['meterBulanIni']) ? (int)$_POST['meterBulanIni'] : 0;

if ($pelangganId <= 0) {
    echo json_encode(['error' => 'Pilih pelanggan terlebih dahulu']);
    exit;
}

if ($meterBulanIni < $meterBulanLalu) {
    echo json_encode(['error' => 'Meter bulan ini tidak boleh lebih kecil dari bulan lalu']);
    exit;
}

// Ambil biaya admin dari kategori pelanggan
$pelanggan = mysqli_fetch_assoc(mysqli_query($conn, 
    "SELECT k.biayaAdm FROM pelanggan p 
     LEFT JOIN kategori k ON p.kategoriId = k.id 
     WHERE p.id = $pelangganId"));

if (!$pelanggan) {
    echo json_encode(['error' => 'Data pelanggan tidak ditemukan']);
    exit;
}

$pemakaian = $meterBulanIni - $meterBulanLalu;
$hargaPerKubikAir = 3500; // Harga per m3
$biayaAdm = (int) $pelanggan['biayaAdm'];
$totalTagihan = ($pemakaian * $hargaPerKubikAir) + $biayaAdm;

echo json_encode([
    'pemakaian' => $pemakaian,
    'hargaPerKubikAir' => $hargaPerKubikAir,
    'biayaAdm' => $biayaAdm,
    'totalTagihan' => $totalTagihan
]);
