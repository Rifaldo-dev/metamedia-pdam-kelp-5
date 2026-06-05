<?php
session_start();
include '../koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];

    $query = "SELECT u.id, u.username, u.password, u.role, k.namaKaryawan 
              FROM users u 
              LEFT JOIN karyawan k ON u.karyawanId = k.id 
              WHERE u.username = '$username'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) === 1) {
        $user = mysqli_fetch_assoc($result);

        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['namaKaryawan'] = $user['namaKaryawan'];

            $now = date('Y-m-d H:i:s');
            mysqli_query($conn, "UPDATE users SET lastLogin = '$now' WHERE id = '{$user['id']}'");

            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Password salah!']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Username tidak ditemukan!']);
    }
    exit;
}

if (!isset($_SESSION['user_id'])) {
    header("Location: auth/login.php");
    exit;
}
