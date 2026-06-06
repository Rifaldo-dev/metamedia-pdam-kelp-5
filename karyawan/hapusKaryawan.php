<?php
session_start();
include '../koneksi.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id > 0) {
    // Cek apakah karyawan masih dipakai di tagihan atau users
    $cekTagihan = mysqli_query($conn, "SELECT id FROM tagihan WHERE karyawanId = $id");
    $cekUser = mysqli_query($conn, "SELECT id FROM users WHERE karyawanId = $id");

    if (mysqli_num_rows($cekTagihan) > 0) {
        $_SESSION['error'] = "Karyawan tidak bisa dihapus karena masih memiliki data tagihan!";
        header("Location: dataKaryawan.php");
        exit;
    }

    if (mysqli_num_rows($cekUser) > 0) {
        $_SESSION['error'] = "Karyawan tidak bisa dihapus karena masih terhubung dengan akun user!";
        header("Location: dataKaryawan.php");
        exit;
    }

    $query = "DELETE FROM karyawan WHERE id = $id";
    if (mysqli_query($conn, $query)) {
        $_SESSION['success'] = "Data karyawan berhasil dihapus!";
    } else {
        $_SESSION['error'] = "Gagal menghapus data: " . mysqli_error($conn);
    }
}

header("Location: dataKaryawan.php");
exit;
