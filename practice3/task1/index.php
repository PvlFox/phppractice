<?php

// Задание 1

function getTextStats($text) {
    // Удаление двойных пробелов
    $cleanText = trim(preg_replace('/\s+/', ' ', $text));
    
    // Подсчёт символов (включая пробелы и знаки)
    $characterCount = mb_strlen($text);

    // Подсчёт слов
    $words = preg_split('/\s+/u', $cleanText, -1, PREG_SPLIT_NO_EMPTY);
    $wordCount = count($words);

    // Подсчёт предложений (повествовательный, восклицательный, вопросительный)
    $sentenceCount = preg_match_all('/[.!?]+/u', $text);

    // Средняя длина слова
    $totalWordLength = array_sum(array_map('mb_strlen', $words));
    $averageWordLength = $wordCount > 0 ? round($totalWordLength / $wordCount, 2) : 0;

    // Словарь частоты
    $wordFrequency = [];
    foreach ($words as $word) {
        $normalized = mb_strtolower(trim($word, ".,!?;:\"'()[]"));
        if ($normalized !== '') {
            $wordFrequency[$normalized] = ($wordFrequency[$normalized] ?? 0) + 1;
        }
    }

    // Самое частое слово
    $maxFreq = max($wordFrequency);
    $mostCommon = array_keys(array_filter($wordFrequency, fn($count) => $count === $maxFreq));

    return [
        'character_count' => $characterCount,
        'word_count' => $wordCount,
        'sentence_count' => $sentenceCount,
        'average_word_length' => $averageWordLength,
        'most_common_word' => $mostCommon
    ];
}

// Пример
$text = "The quick brown fox jumps over the lazy dog. The dog was not amused.";
$stats = getTextStats($text);
print_r($stats);
?>
