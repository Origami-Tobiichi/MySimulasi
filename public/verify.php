<?php
session_start();
$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $otp = trim($_POST['otp']);

    if ($otp == $_SESSION['otp']) {
        $usersFile = '../users.json';
        $users = file_exists($usersFile) ? json_decode(file_get_contents($usersFile), true) : [];
        $tempUser = $_SESSION['temp_user'];

        $users[$tempUser['username']] = [
            'email' => $tempUser['email'],
            'full_name' => $tempUser['full_name'],
            'password' => $tempUser['password'],
            'verified' => true
        ];

        file_put_contents($usersFile, json_encode($users, JSON_PRETTY_PRINT));
        unset($_SESSION['otp'], $_SESSION['temp_user']);
        header('Location: login.php');
        exit;
    } else {
        $error = "Kode OTP salah.";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head><title>Verifikasi</title></head>
<body>
    <form method="POST">
        <input type="text" name="otp" placeholder="Masukkan Kode OTP" required>
        <button type="submit">Verifikasi</button>
    </form>
    <?php if ($error): ?><p><?= $error ?></p><?php endif; ?>
</body>
</html>