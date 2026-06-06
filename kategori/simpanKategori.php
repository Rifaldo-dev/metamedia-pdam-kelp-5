<?php
session_start();
include '../koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $kodeKategori = mysqli_real_escape_string($conn, trim($_POST['kodeKategori']));
    $namaKategori = mysqli_real_escape_string($conn, trim($_POST['namaKategori']));
    $biayaAdm = (int) $_POST['biayaAdm'];

    // Cek duplikat kode
    $cek = mysqli_query($conn, "SELECT id FROM kategori WHERE kodeKategori = '$kodeKategori'");
    if (mysqli_num_rows($cek) > 0) {
        $_SESSION['error'] = "Kode kategori '$kodeKategori' sudah digunakan!";
        header("Location: tambahKategori.php");
        exit;
    }

    $query = "INSERT INTO kategori (kodeKategori, namaKategori, biayaAdm, createdAt, updatedAt) 
              VALUES ('$kodeKategori', '$namaKategori', $biayaAdm, NOW(), NOW())";

    if (mysqli_query($conn, $query)) {
        $_SESSION['success'] = "Data kategori berhasil ditambahkan!";
    } else {
        $_SESSION['error'] = "Gagal menambahkan data: " . mysqli_error($conn);
    }
}

header("Location: dataKategori.php");
exit;
