<?php
session_start(); // Pastikan session dimulai sebelum ada output apapun

// Cek apakah pengguna sudah login dan memiliki fullname
if (!isset($_SESSION['user']) || !isset($_SESSION['fullname'])) {
    header('Location: login.php'); // Jika belum login atau fullname belum tersedia, redirect ke halaman login
    exit;
}

// Ambil fullname dari session
$fullname = $_SESSION['fullname'];

// Menyertakan file yang diperlukan
include_once '../includes/auth.php';
include_once '../includes/exam_utils.php';

// Memeriksa apakah file JSON ada dan dapat dibaca
$questionsFile = '../data/questions.json';

if (!file_exists($questionsFile) || !is_readable($questionsFile)) {
    die('File soal tidak ditemukan atau tidak dapat dibaca.');
}

// Memuat soal dari JSON
$questions = json_decode(file_get_contents($questionsFile), true);

// Memeriksa apakah data soal berhasil dimuat
if ($questions === null) {
    die('Gagal memuat soal dari file JSON.');
}

// Proses soal untuk memastikan pilihan C dan D sesuai dengan kata "benar" dan "salah"
foreach ($questions as &$question) {
    $options = $question['options'];
    $normalOptions = [];

    // Pisahkan opsi yang mengandung kata "benar" atau "salah"
    foreach ($options as $option) {
        if (stripos($option, 'A dan B benar') !== false) {
            $question['options'][2] = $option; // C
        } elseif (stripos($option, 'A, B, dan C salah') !== false) {
            $question['options'][3] = $option; // D
        } else {
            $normalOptions[] = $option;
        }
    }

    // Pastikan ada 4 opsi dengan penempatan yang benar
    $question['options'] = array_merge(
        array_slice($normalOptions, 0, 2), // Tempatkan 2 opsi normal di A dan B
        array_slice($question['options'], 2, 2) // Tempatkan opsi C dan D sesuai aturan
    );
}

// Menyimpan soal yang telah diubah ke session
$_SESSION['questions'] = $questions;

// Tentukan jumlah soal per halaman
$perPage = 10;
$totalQuestions = count($questions);
$totalPages = ceil($totalQuestions / $perPage);

// Menentukan halaman saat ini
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;
if ($page > $totalPages) $page = $totalPages;

// Menampilkan soal sesuai halaman saat ini
$start = ($page - 1) * $perPage;
$questionsToDisplay = array_slice($questions, $start, $perPage);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ujian</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .container {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            padding: 20px;
        }
        .sidebar {
            width: 100%;
            background-color: #007BFF;
            color: white;
            padding: 20px;
            box-sizing: border-box;
        }
        .content {
            width: 100%;
            padding: 20px;
            background-color: #fff;
            margin-top: 20px;
        }
        .button {
            padding: 10px 20px;
            margin-top: 20px;
            background-color: #007BFF;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            display: inline-block;
            text-align: center;
        }
        .button:hover {
            background-color: #0056b3;
        }
        .logout-btn {
            margin-top: 10px;
            background-color: #dc3545;
        }
        h3 {
            margin-top: 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="sidebar">
            <!-- Menampilkan nama lengkap peserta di bagian atas kiri -->
            <h2>Selamat datang, <?= htmlspecialchars($_SESSION['fullname'], ENT_QUOTES, 'UTF-8') ?></h2>
            <a href="logout.php" class="button logout-btn">Logout</a>
        </div>

        <div class="content">
            <h3>Mulai Ujian</h3>
            <form method="POST" action="result.php">
                <?php foreach ($questionsToDisplay as $index => $question): ?>
                    <p><?= ($start + $index + 1) . ". " . htmlspecialchars($question['question'], ENT_QUOTES, 'UTF-8') ?></p>
                    <?php 
                    $options = $question['options']; 
                    $letters = ['A', 'B', 'C', 'D']; 
                    foreach ($options as $i => $option): ?>
                        <label>
                            <input type="radio" name="q<?= $start + $index ?>" value="<?= htmlspecialchars($option, ENT_QUOTES, 'UTF-8') ?>" required> 
                            <?= $letters[$i] ?>. <?= htmlspecialchars($option, ENT_QUOTES, 'UTF-8') ?>
                        </label>
                        <br>
                    <?php endforeach; ?>
                <?php endforeach; ?>

                <!-- Navigation buttons -->
                <div>
                    <?php if ($page > 1): ?>
                        <a href="?page=<?= $page - 1 ?>" class="button">Kembali</a>
                    <?php endif; ?>

                    <?php if ($page < $totalPages): ?>
                        <a href="?page=<?= $page + 1 ?>" class="button">Selanjutnya</a>
                    <?php endif; ?>
                </div>

                <!-- Submit only if it's the last page -->
                <?php if ($page == $totalPages): ?>
                    <button type="submit" class="button">Kirim Jawaban</button>
                <?php endif; ?>
            </form>
        </div>
    </div>
</body>
</html>