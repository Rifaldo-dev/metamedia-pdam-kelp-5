<?php
include '../koneksi.php';

header('Content-Type: application/json');

$search = isset($_GET['q']) ? mysqli_real_escape_string($conn, $_GET['q']) : '';
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 10;
$offset = ($page - 1) * $limit;

$where = '';
if ($search) {
    $where = "WHERE nik LIKE '%$search%' OR namaKaryawan LIKE '%$search%' OR jabatan LIKE '%$search%'";
}

// Count total
$totalData = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(id) as total FROM karyawan $where"))['total'];
$totalPages = ceil($totalData / $limit);

// Get data
$query = "SELECT * FROM karyawan $where ORDER BY id DESC LIMIT $limit OFFSET $offset";
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
