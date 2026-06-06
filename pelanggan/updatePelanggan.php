<?php
session_start();
include '../koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int) $_POST['id'];
    $noRekening = mysqli_real_escape_string($conn, trim($_POST['noRekening']));
    $namaPelanggan = mysqli_real_escape_string($conn, trim($_POST['namaPelanggan']));
    $alamat = mysqli_real_escape_string($conn, trim($_POST['alamat']));
    $noHp = mysqli_real_escape_string($conn, trim($_POST['noHp']));
    $kategoriId = (int) $_POST['kategoriId'];

    // Cek duplikat no rekening (kecuali diri sendiri)
    $cek = mysqli_query($conn, "SELECT id FROM pelanggan WHERE noRekening = '$noRekening' AND id != $id");
    if (mysqli_num_rows($cek) > 0) {
        $_SESSION['error'] = "No Rekening '$noRekening' sudah digunakan pelanggan lain!";
        header("Location: editPelanggan.php?id=$id");
        exit;
    }

    $query = "UPDATE pelanggan SET 
              noRekening = '$noRekening', 
              namaPelanggan = '$namaPelanggan', 
              alamat = '$alamat', 
              noHp = '$noHp', 
              kategoriId = $kategoriId, 
              updatedAt = NOW() 
              WHERE id = $id";

    if (mysqli_query($conn, $query)) {
        $_SESSION['success'] = "Data pelanggan berhasil diupdate!";
    } else {
        $_SESSION['error'] = "Gagal mengupdate data: " . mysqli_error($conn);
    }
}

header("Location: dataPelanggan.php");
exit;
