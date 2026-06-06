<?php
include '../koneksi.php';

header('Content-Type: application/json');

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id <= 0) {
    echo json_encode(['error' => 'ID tidak valid']);
    exit;
}

$query = "SELECT t.*, p.noRekening, p.namaPelanggan, p.alamat, k.namaKategori 
          FROM tagihan t 
          JOIN pelanggan p ON t.pelangganId = p.id 
          LEFT JOIN kategori k ON p.kategoriId = k.id 
          WHERE t.id = $id";

$data = mysqli_fetch_assoc(mysqli_query($conn, $query));

if ($data) {
    echo json_encode($data);
} else {
    echo json_encode(['error' => 'Data tidak ditemukan']);
}
