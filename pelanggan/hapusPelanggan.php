<?php
session_start();
include '../koneksi.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id > 0) {
    // Cek apakah pelanggan masih punya tagihan
    $cek = mysqli_query($conn, "SELECT id FROM tagihan WHERE pelangganId = $id");
    if (mysqli_num_rows($cek) > 0) {
        $_SESSION['error'] = "Pelanggan tidak bisa dihapus karena masih memiliki data tagihan!";
        header("Location: dataPelanggan.php");
        exit;
    }

    $query = "DELETE FROM pelanggan WHERE id = $id";
    if (mysqli_query($conn, $query)) {
        $_SESSION['success'] = "Data pelanggan berhasil dihapus!";
    } else {
        $_SESSION['error'] = "Gagal menghapus data: " . mysqli_error($conn);
    }
}

header("Location: dataPelanggan.php");
exit;
