<?php
function loadQuestions() {
    // Cek apakah file questions.json ada
    $filePath = __DIR__ . '/../data/questions.json';
    if (!file_exists($filePath)) {
        // Jika file tidak ada, tampilkan pesan error dan hentikan eksekusi
        die("File questions.json tidak ditemukan.");
    }

    // Coba membaca file dan decode JSON
    $data = file_get_contents($filePath);
    if ($data === false) {
        // Jika file gagal dibaca, tampilkan pesan error
        die("Gagal membaca file questions.json.");
    }

    $questions = json_decode($data, true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        // Jika JSON tidak valid, tampilkan pesan error
        die("Format JSON dalam file questions.json tidak valid.");
    }

    return $questions;
}

function shuffleQuestions($questions) {
    // Pastikan parameter adalah array
    if (!is_array($questions)) {
        die("Parameter yang diberikan bukan array.");
    }

    foreach ($questions as &$question) {
        // Cek jika setiap soal memiliki opsi, dan shuffle opsinya
        if (isset($question['options']) && is_array($question['options'])) {
            shuffle($question['options']);
        } else {
            die("Opsi untuk soal tidak ditemukan atau tidak valid.");
        }
    }

    shuffle($questions); // Acak urutan soal
    return $questions;
}
?>