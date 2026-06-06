<?php
session_start();
include '../koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int) $_POST['id'];
    $kodeKategori = mysqli_real_escape_string($conn, trim($_POST['kodeKategori']));
    $namaKategori = mysqli_real_escape_string($conn, trim($_POST['namaKategori']));
    $biayaAdm = (int) $_POST['biayaAdm'];

    // Cek duplikat kode (kecuali diri sendiri)
    $cek = mysqli_query($conn, "SELECT id FROM kategori WHERE kodeKategori = '$kodeKategori' AND id != $id");
    if (mysqli_num_rows($cek) > 0) {
        $_SESSION['error'] = "Kode kategori '$kodeKategori' sudah digunakan!";
        header("Location: editKategori.php?id=$id");
        exit;
    }

    $query = "UPDATE kategori SET 
              kodeKategori = '$kodeKategori', 
              namaKategori = '$namaKategori', 
              biayaAdm = $biayaAdm, 
              updatedAt = NOW() 
              WHERE id = $id";

    if (mysqli_query($conn, $query)) {
        $_SESSION['success'] = "Data kategori berhasil diupdate!";
    } else {
        $_SESSION['error'] = "Gagal mengupdate data: " . mysqli_error($conn);
    }
}

header("Location: dataKategori.php");
exit;
