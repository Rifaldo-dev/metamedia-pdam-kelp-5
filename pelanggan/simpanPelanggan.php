<?php
session_start();
include '../koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $noRekening = mysqli_real_escape_string($conn, trim($_POST['noRekening']));
    $namaPelanggan = mysqli_real_escape_string($conn, trim($_POST['namaPelanggan']));
    $alamat = mysqli_real_escape_string($conn, trim($_POST['alamat']));
    $noHp = mysqli_real_escape_string($conn, trim($_POST['noHp']));
    $kategoriId = (int) $_POST['kategoriId'];

    // Cek duplikat no rekening
    $cek = mysqli_query($conn, "SELECT id FROM pelanggan WHERE noRekening = '$noRekening'");
    if (mysqli_num_rows($cek) > 0) {
        $_SESSION['error'] = "No Rekening '$noRekening' sudah terdaftar!";
        header("Location: tambahPelanggan.php");
        exit;
    }

    $query = "INSERT INTO pelanggan (noRekening, namaPelanggan, alamat, noHp, kategoriId, createdAt, updatedAt) 
              VALUES ('$noRekening', '$namaPelanggan', '$alamat', '$noHp', $kategoriId, NOW(), NOW())";

    if (mysqli_query($conn, $query)) {
        $_SESSION['success'] = "Data pelanggan berhasil ditambahkan!";
    } else {
        $_SESSION['error'] = "Gagal menambahkan data: " . mysqli_error($conn);
    }
}

header("Location: dataPelanggan.php");
exit;
