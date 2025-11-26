<?php

$filePath = "src/txt/pg41445.txt";


if (!file_exists($filePath)) {
    echo "Файл '$filePath' не существует или указан неверный путь!";
    exit;
}

function countWords($file) {
    $fp = fopen($file, "r"); 
    if (!$fp) return 0;

    $content = fread($fp, filesize($file)); 
    fclose($fp); 

    return str_word_count($content); 
}


$wordCount = countWords($filePath);
echo "Количество слов в файле [$filePath]: $wordCount";
?>
