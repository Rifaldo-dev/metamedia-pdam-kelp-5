<?php
session_start();
include '../koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int) $_POST['id'];
    $nik = mysqli_real_escape_string($conn, trim($_POST['nik']));
    $namaKaryawan = mysqli_real_escape_string($conn, trim($_POST['namaKaryawan']));
    $jabatan = mysqli_real_escape_string($conn, trim($_POST['jabatan']));
    $noHp = mysqli_real_escape_string($conn, trim($_POST['noHp']));
    $alamat = mysqli_real_escape_string($conn, trim($_POST['alamat']));

    // Cek duplikat NIK (kecuali diri sendiri)
    $cek = mysqli_query($conn, "SELECT id FROM karyawan WHERE nik = '$nik' AND id != $id");
    if (mysqli_num_rows($cek) > 0) {
        $_SESSION['error'] = "NIK '$nik' sudah digunakan karyawan lain!";
        header("Location: editKaryawan.php?id=$id");
        exit;
    }

    $query = "UPDATE karyawan SET 
              nik = '$nik', 
              namaKaryawan = '$namaKaryawan', 
              jabatan = '$jabatan', 
              noHp = '$noHp', 
              alamat = '$alamat', 
              updatedAt = NOW() 
              WHERE id = $id";

    if (mysqli_query($conn, $query)) {
        $_SESSION['success'] = "Data karyawan berhasil diupdate!";
    } else {
        $_SESSION['error'] = "Gagal mengupdate data: " . mysqli_error($conn);
    }
}

header("Location: dataKaryawan.php");
exit;
