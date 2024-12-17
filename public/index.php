<?php
session_start();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <title>Halaman Utama</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            margin-top: 50px;
        }
        .button {
            padding: 10px 20px;
            margin: 10px;
            background-color: #007BFF;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            display: inline-block;
        }
        .button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <h1>Selamat Datang di Sistem Ujian</h1>
    <p>Silakan login untuk memulai ujian Anda.</p>
    <a href="login.php" class="button">Login</a>
    <a href="register.php" class="button">Daftar</a>
</body>
</html>