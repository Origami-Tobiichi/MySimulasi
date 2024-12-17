<?php
session_start();
$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $usersFile = '../users.json';

    if (file_exists($usersFile)) {
        $users = json_decode(file_get_contents($usersFile), true);

        if (isset($users[$username]) && password_verify($password, $users[$username]['password'])) {
            if ($users[$username]['verified']) {
                $_SESSION['user'] = $username;
                header('Location: dashboard.php');
                exit;
            } else {
                $error = "Akun belum diverifikasi.";
            }
        } else {
            $error = "Username atau password salah.";
        }
    } else {
        $error = "Belum ada pengguna terdaftar.";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <title>Login Peserta</title>
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
    <h1>Login Peserta</h1>
    <div class="form-container">
        <form method="POST" action="">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>
            <br>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
            <br>
            <button type="submit">Login</button>
        </form>
        <?php if (!empty($error)): ?>
            <p class="error"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></p>
        <?php endif; ?>
    </div>
    <p>Belum punya akun? <a href="register.php">Daftar di sini</a></p>
</body>
</html>