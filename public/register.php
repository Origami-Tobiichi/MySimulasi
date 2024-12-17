<?php
require 'send_otp.php';

session_start();
$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $username = trim($_POST['username']);
    $fullName = trim($_POST['full_name']);
    $password = trim($_POST['password']);
    $confirmPassword = trim($_POST['confirm_password']);

    if (empty($email) || empty($username) || empty($fullName) || empty($password)) {
        $error = "Semua kolom wajib diisi.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Format email tidak valid.";
    } elseif ($password !== $confirmPassword) {
        $error = "Password dan konfirmasi password tidak cocok.";
    } else {
        $usersFile = '../users.json';
        $users = file_exists($usersFile) ? json_decode(file_get_contents($usersFile), true) : [];

        if (isset($users[$username])) {
            $error = "Username sudah terdaftar.";
        } else {
            $otp = random_int(100000, 999999);
            $_SESSION['otp'] = $otp;
            $_SESSION['temp_user'] = [
                'email' => $email,
                'username' => $username,
                'full_name' => $fullName,
                'password' => password_hash($password, PASSWORD_DEFAULT),
                'verified' => false
            ];

            if (sendOtpEmail($email, $otp)) {
                header('Location: ../public/verify.php');
                exit;
            } else {
                $error = "Gagal mengirim email verifikasi.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Peserta</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            margin-top: 50px;
        }
        .form-container {
            display: inline-block;
            text-align: left;
            margin-top: 20px;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .form-container input {
            width: 200px;
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .form-container button {
            padding: 10px 20px;
            background-color: #007BFF;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .form-container button:hover {
            background-color: #0056b3;
        }
        .error {
            color: red;
        }
    </style>
</head>
<body>
    <h1>Daftar Peserta</h1>
    <div class="form-container">
        <!-- Formulir Registrasi -->
        <form method="POST" action="">
            <label for="full_name">Nama Lengkap:</label>
            <input type="text" id="full_name" name="full_name" required>
            <br>
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>
            <br>
            <label for="email">E-mail:</label>
            <input type="email" id="email" name="email" placeholder="Email" required>
            <br>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
            <br>
            <label for="confirm_password">Konfirmasi Password:</label>
            <input type="password" id="confirm_password" name="confirm_password" required>
            <br>
            <button type="submit">Daftar</button>
        </form>

        <?php 
            // Proses Registrasi
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $full_name = $_POST['full_name'];
                $username = $_POST['username'];
                $email = $_POST['email'];
                $password = $_POST['password'];
                $confirm_password = $_POST['confirm_password'];

                // Validasi password
                if ($password !== $confirm_password) {
                    echo '<p class="error">Password dan konfirmasi password tidak cocok!</p>';
                } else {
                    // Jika password cocok, lanjutkan ke verifikasi OTP
                    echo '<h2>Verifikasi Email</h2>';
                    echo '<form method="POST" action="send_otp.php">';
                    echo '<label for="email">Masukkan Alamat Email Anda:</label>';
                    echo '<input type="email" id="email" name="email" value="'.$email.'" required>';
                    echo '<button type="submit">Kirim OTP</button>';
                    echo '</form>';
                }
            }
        ?>
    </div>
    <p>Sudah punya akun? <a href="login.php">Login di sini</a></p>
</body>
</html>