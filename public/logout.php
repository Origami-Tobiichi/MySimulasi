<?php
session_start();

// Hapus session yang aktif
session_unset();
session_destroy();

// Redirect ke halaman login setelah logout
header('Location: login.php');
exit;
?>