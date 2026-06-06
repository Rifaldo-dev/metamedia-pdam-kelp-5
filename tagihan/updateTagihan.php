<?php
session_start();
include '../koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int) $_POST['id'];
    $pelangganId = (int) $_POST['pelangganId'];
    $periodeBulan = (int) $_POST['periodeBulan'];
    $periodeTahun = (int) $_POST['periodeTahun'];
    $tglTagih = mysqli_real_escape_string($conn, $_POST['tglTagih']);
    $meterBulanLalu = (int) $_POST['meterBulanLalu'];
    $meterBulanIni = (int) $_POST['meterBulanIni'];
    $statusBayar = mysqli_real_escape_string($conn, $_POST['statusBayar']);

    // Validasi
    if ($meterBulanIni < $meterBulanLalu) {
        $_SESSION['error'] = "Meter bulan ini tidak boleh lebih kecil dari bulan lalu!";
        header("Location: editTagihan.php?id=$id");
        exit;
    }

    // Cek duplikat periode (kecuali diri sendiri)
    $cek = mysqli_query($conn, "SELECT id FROM tagihan WHERE pelangganId = $pelangganId AND periodeBulan = $periodeBulan AND periodeTahun = $periodeTahun AND id != $id");
    if (mysqli_num_rows($cek) > 0) {
        $_SESSION['error'] = "Tagihan untuk periode $periodeBulan/$periodeTahun sudah ada!";
        header("Location: editTagihan.php?id=$id");
        exit;
    }

    // Hitung ulang tagihan
    $pemakaian = $meterBulanIni - $meterBulanLalu;
    $hargaPerKubikAir = 3500;

    $pelanggan = mysqli_fetch_assoc(mysqli_query($conn, 
        "SELECT k.biayaAdm FROM pelanggan p LEFT JOIN kategori k ON p.kategoriId = k.id WHERE p.id = $pelangganId"));
    $biayaAdm = (int) ($pelanggan['biayaAdm'] ?? 0);

    $totalTagihan = ($pemakaian * $hargaPerKubikAir) + $biayaAdm;

    $query = "UPDATE tagihan SET 
              periodeBulan = $periodeBulan, 
              periodeTahun = $periodeTahun, 
              tglTagih = '$tglTagih', 
              meterBulanLalu = $meterBulanLalu, 
              meterBulanIni = $meterBulanIni, 
              pemakaian = $pemakaian, 
              hargaPerKubikAir = $hargaPerKubikAir, 
              biayaAdm = $biayaAdm, 
              totalTagihan = $totalTagihan, 
              statusBayar = '$statusBayar', 
              updatedAt = NOW() 
              WHERE id = $id";

    if (mysqli_query($conn, $query)) {
        $_SESSION['success'] = "Tagihan berhasil diupdate!";
    } else {
        $_SESSION['error'] = "Gagal mengupdate tagihan: " . mysqli_error($conn);
    }
}

header("Location: dataTagihan.php");
exit;
