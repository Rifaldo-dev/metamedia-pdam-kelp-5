<?php
session_start();
include '../koneksi.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id > 0) {
    // Cek apakah kategori masih dipakai pelanggan
    $cek = mysqli_query($conn, "SELECT id FROM pelanggan WHERE kategoriId = $id");
    if (mysqli_num_rows($cek) > 0) {
        $_SESSION['error'] = "Kategori tidak bisa dihapus karena masih digunakan oleh pelanggan!";
        header("Location: dataKategori.php");
        exit;
    }

    $query = "DELETE FROM kategori WHERE id = $id";
    if (mysqli_query($conn, $query)) {
        $_SESSION['success'] = "Data kategori berhasil dihapus!";
    } else {
        $_SESSION['error'] = "Gagal menghapus data: " . mysqli_error($conn);
    }
}

header("Location: dataKategori.php");
exit;
