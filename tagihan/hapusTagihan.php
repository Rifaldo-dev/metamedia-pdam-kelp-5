<?php
session_start();
include '../koneksi.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id > 0) {
    $query = "DELETE FROM tagihan WHERE id = $id";
    if (mysqli_query($conn, $query)) {
        $_SESSION['success'] = "Data tagihan berhasil dihapus!";
    } else {
        $_SESSION['error'] = "Gagal menghapus data: " . mysqli_error($conn);
    }
}

header("Location: dataTagihan.php");
exit;
