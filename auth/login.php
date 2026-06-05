<?php
session_start();

// Jika sudah login, redirect ke dashboard
if (isset($_SESSION['user_id'])) {
    header("Location: ../dashboard.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - PDAM Zernih</title>
    <link rel="stylesheet" href="../assets/css/login.css">
</head>
<body>
    <div class="login-container">
        <div class="login-header">
            <h1>PDAM Zernih</h1>
            <p>Sistem Informasi Rekening Air</p>
        </div>

        <div id="errorMsg" class="error-msg" style="display: none;"></div>

        <form id="formLogin">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" required placeholder="Masukkan username">
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <div class="password-wrapper">
                    <input type="password" id="password" name="password" required placeholder="Masukkan password">
                    <span class="toggle-password" onclick="togglePassword()">&#128065;</span>
                </div>
            </div>
            <button type="submit" class="btn-login">Login</button>
        </form>
    </div>

    <script>
        function togglePassword() {
            let input = document.getElementById('password');
            input.type = input.type === 'password' ? 'text' : 'password';
        }

        document.getElementById('formLogin').addEventListener('submit', function(e) {
            e.preventDefault();

            let username = document.getElementById('username').value;
            let password = document.getElementById('password').value;

            fetch('cekLogin.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `username=${encodeURIComponent(username)}&password=${encodeURIComponent(password)}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    window.location.href = '../dashboard.php';
                } else {
                    let errorMsg = document.getElementById('errorMsg');
                    errorMsg.textContent = data.message;
                    errorMsg.style.display = 'block';
                }
            });
        });
    </script>
</body>
</html>
