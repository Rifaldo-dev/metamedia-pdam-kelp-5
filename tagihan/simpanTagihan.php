<?php
session_start();
include '../koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pelangganId = (int) $_POST['pelangganId'];
    $karyawanId = (int) ($_POST['karyawanId'] ?: ($_SESSION['karyawanId'] ?? 0));
    $periodeBulan = (int) $_POST['periodeBulan'];
    $periodeTahun = (int) $_POST['periodeTahun'];
    $tglTagih = mysqli_real_escape_string($conn, $_POST['tglTagih']);
    $meterBulanLalu = (int) $_POST['meterBulanLalu'];
    $meterBulanIni = (int) $_POST['meterBulanIni'];

    // Validasi karyawanId
    if ($karyawanId <= 0) {
        // Ambil karyawanId dari user yang login
        $userId = $_SESSION['user_id'] ?? 0;
        $userRow = mysqli_fetch_assoc(mysqli_query($conn, "SELECT karyawanId FROM users WHERE id = $userId"));
        $karyawanId = (int) ($userRow['karyawanId'] ?? 0);
    }

    if ($karyawanId <= 0) {
        $_SESSION['error'] = "Karyawan tidak teridentifikasi. Silakan logout dan login ulang!";
        header("Location: tambahTagihan.php");
        exit;
    }

    // Validasi
    if ($meterBulanIni < $meterBulanLalu) {
        $_SESSION['error'] = "Meter bulan ini tidak boleh lebih kecil dari bulan lalu!";
        header("Location: tambahTagihan.php");
        exit;
    }

    // Cek duplikat tagihan periode yang sama untuk pelanggan yang sama
    $cek = mysqli_query($conn, "SELECT id FROM tagihan WHERE pelangganId = $pelangganId AND periodeBulan = $periodeBulan AND periodeTahun = $periodeTahun");
    if (mysqli_num_rows($cek) > 0) {
        $_SESSION['error'] = "Tagihan untuk pelanggan ini pada periode $periodeBulan/$periodeTahun sudah ada!";
        header("Location: tambahTagihan.php");
        exit;
    }

    // Hitung tagihan
    $pemakaian = $meterBulanIni - $meterBulanLalu;
    $hargaPerKubikAir = 3500;

    // Ambil biaya admin dari kategori pelanggan
    $pelanggan = mysqli_fetch_assoc(mysqli_query($conn, 
        "SELECT k.biayaAdm FROM pelanggan p LEFT JOIN kategori k ON p.kategoriId = k.id WHERE p.id = $pelangganId"));
    $biayaAdm = (int) ($pelanggan['biayaAdm'] ?? 0);

    $totalTagihan = ($pemakaian * $hargaPerKubikAir) + $biayaAdm;

    $query = "INSERT INTO tagihan (pelangganId, karyawanId, periodeBulan, periodeTahun, tglTagih, meterBulanLalu, meterBulanIni, pemakaian, hargaPerKubikAir, biayaAdm, totalTagihan, statusBayar, createdAt, updatedAt) 
              VALUES ($pelangganId, $karyawanId, $periodeBulan, $periodeTahun, '$tglTagih', $meterBulanLalu, $meterBulanIni, $pemakaian, $hargaPerKubikAir, $biayaAdm, $totalTagihan, 'Belum Bayar', NOW(), NOW())";

    if (mysqli_query($conn, $query)) {
        $_SESSION['success'] = "Tagihan berhasil disimpan! Total: Rp " . number_format($totalTagihan, 0, ',', '.');
    } else {
        $_SESSION['error'] = "Gagal menyimpan tagihan: " . mysqli_error($conn);
    }
}

header("Location: dataTagihan.php");
exit;
