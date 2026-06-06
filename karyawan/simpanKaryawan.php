<?php
session_start();
include '../koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nik = mysqli_real_escape_string($conn, trim($_POST['nik']));
    $namaKaryawan = mysqli_real_escape_string($conn, trim($_POST['namaKaryawan']));
    $jabatan = mysqli_real_escape_string($conn, trim($_POST['jabatan']));
    $noHp = mysqli_real_escape_string($conn, trim($_POST['noHp']));
    $alamat = mysqli_real_escape_string($conn, trim($_POST['alamat']));

    // Cek duplikat NIK
    $cek = mysqli_query($conn, "SELECT id FROM karyawan WHERE nik = '$nik'");
    if (mysqli_num_rows($cek) > 0) {
        $_SESSION['error'] = "NIK '$nik' sudah terdaftar!";
        header("Location: tambahKaryawan.php");
        exit;
    }

    $query = "INSERT INTO karyawan (nik, namaKaryawan, jabatan, noHp, alamat, createdAt, updatedAt) 
              VALUES ('$nik', '$namaKaryawan', '$jabatan', '$noHp', '$alamat', NOW(), NOW())";

    if (mysqli_query($conn, $query)) {
        $_SESSION['success'] = "Data karyawan berhasil ditambahkan!";
    } else {
        $_SESSION['error'] = "Gagal menambahkan data: " . mysqli_error($conn);
    }
}

header("Location: dataKaryawan.php");
exit;
