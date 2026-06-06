<?php
include '../koneksi.php';

header('Content-Type: application/json');

$search = isset($_GET['q']) ? mysqli_real_escape_string($conn, $_GET['q']) : '';

if (strlen($search) < 2) {
    echo json_encode([]);
    exit;
}

$query = "SELECT t.*, p.noRekening, p.namaPelanggan 
          FROM tagihan t 
          JOIN pelanggan p ON t.pelangganId = p.id 
          WHERE p.noRekening LIKE '%$search%' OR p.namaPelanggan LIKE '%$search%' 
          ORDER BY t.id DESC 
          LIMIT 10";

$result = mysqli_query($conn, $query);
$data = [];

while ($row = mysqli_fetch_assoc($result)) {
    $data[] = $row;
}

echo json_encode($data);
