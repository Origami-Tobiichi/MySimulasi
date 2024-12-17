<?php
session_start();

// Validasi soal di sesi
if (!isset($_SESSION['questions']) || !is_array($_SESSION['questions']) || empty($_SESSION['questions'])) {
    echo "<p>Soal tidak ditemukan. Silakan kembali ke halaman login untuk memulai ujian.</p>";
    echo "<a href='login.php'>Kembali ke Login</a>";
    exit;
}

$questions = $_SESSION['questions'];
$userAnswers = $_POST;

// Validasi jika ada jawaban kosong
foreach ($questions as $index => $question) {
    if (!isset($userAnswers["q$index"]) || !in_array($userAnswers["q$index"], $question['options'])) {
        echo "<p style='color: red;'>Anda belum menjawab semua soal atau jawaban Anda tidak valid. Silakan kembali dan selesaikan ujian.</p>";
        echo "<a href='exam.php'>Kembali ke Ujian</a>";
        exit;
    }
}

$score = 0;
$total = count($questions);

echo "<h2>Hasil Ujian</h2>";

// Tampilkan soal, jawaban benar, dan jawaban pengguna
foreach ($questions as $index => $question) {
    $correctAnswer = $question['correct']; // Anggap 'correct' adalah array yang berisi jawaban benar (misalnya ['A', 'C'])
    $userAnswer = $userAnswers["q$index"] ?? '';

    // Sanitasi data sebelum ditampilkan
    $questionText = htmlspecialchars($question['question'], ENT_QUOTES, 'UTF-8');
    echo "<p>$questionText</p>";

    $letters = ['A', 'B', 'C', 'D']; // Array untuk label A, B, C, D
    foreach ($question['options'] as $i => $option) {
        $sanitizedOption = htmlspecialchars($option, ENT_QUOTES, 'UTF-8');

        // Cek apakah pilihan ini adalah jawaban yang benar atau yang dipilih oleh pengguna
        $isCorrect = in_array($letters[$i], $correctAnswer);  // Cek apakah pilihan ini ada dalam jawaban benar (array)
        if ($isCorrect && $option === $userAnswer) {
            echo "<span style='color: green;'>{$letters[$i]}. $sanitizedOption (Benar)</span><br>";
        } elseif (!$isCorrect && $option === $userAnswer) {
            echo "<span style='color: red;'>{$letters[$i]}. $sanitizedOption (Salah)</span><br>";
        }
    }

    // Hitung skor
    if (in_array($userAnswer, $correctAnswer)) {
        $score++;
    }
}

// Tampilkan hasil akhir
echo "<h3>Nilai Anda: $score / $total</h3>";

// Tombol untuk memulai ujian kembali
echo "<form action='exam.php' method='GET'>";
echo "<button type='submit'>Mulai Ujian Lagi</button>";
echo "</form>";

// Tombol untuk keluar
echo "<form action='logout.php' method='POST'>";
echo "<button type='submit'>Keluar</button>";
echo "</form>";
?>