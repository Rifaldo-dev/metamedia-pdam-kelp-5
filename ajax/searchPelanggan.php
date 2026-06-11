<?php
include '../koneksi.php';

header('Content-Type: application/json');

$search = isset($_GET['q']) ? mysqli_real_escape_string($conn, $_GET['q']) : '';
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 10;
$offset = ($page - 1) * $limit;

$where = '';
if ($search) {
    $where = "WHERE p.noRekening LIKE '%$search%' OR p.namaPelanggan LIKE '%$search%' OR p.alamat LIKE '%$search%'";
}

// Count total
$totalData = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(p.id) as total FROM pelanggan p $where"))['total'];
$totalPages = ceil($totalData / $limit);

// Get data
$query = "SELECT p.*, k.namaKategori 
          FROM pelanggan p 
          LEFT JOIN kategori k ON p.kategoriId = k.id 
          $where 
          ORDER BY p.id DESC 
          LIMIT $limit OFFSET $offset";
$result = mysqli_query($conn, $query);
$data = [];

while ($row = mysqli_fetch_assoc($result)) {
    $data[] = $row;
}

echo json_encode([
    'data' => $data,
    'total' => (int)$totalData,
    'totalPages' => (int)$totalPages,
    'page' => $page
]);
