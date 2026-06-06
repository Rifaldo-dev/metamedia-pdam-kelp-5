<?php
include '../koneksi.php';

header('Content-Type: application/json');

$search = isset($_GET['q']) ? mysqli_real_escape_string($conn, $_GET['q']) : '';

if (strlen($search) < 2) {
    echo json_encode([]);
    exit;
}

$query = "SELECT p.id, p.noRekening, p.namaPelanggan, p.alamat, k.namaKategori 
          FROM pelanggan p 
          LEFT JOIN kategori k ON p.kategoriId = k.id 
          WHERE p.noRekening LIKE '%$search%' OR p.namaPelanggan LIKE '%$search%' 
          LIMIT 10";

$result = mysqli_query($conn, $query);
$data = [];

while ($row = mysqli_fetch_assoc($result)) {
    $data[] = $row;
}

echo json_encode($data);
