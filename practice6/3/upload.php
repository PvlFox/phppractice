<?php
// Максимальный размер файла 2 МБ
$maxFileSize = 2 * 1024 * 1024;

// Папка для загрузки
$uploadDir = __DIR__ . '/uploads/';

// Создаём папку uploads, если её нет
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

// Проверка, что файл загружен
if (!isset($_FILES['csvfile']) || $_FILES['csvfile']['error'] !== UPLOAD_ERR_OK) {
    die("Ошибка: файл не выбран или возникла ошибка при загрузке.");
}

// Получаем данные о файле
$fileTmp = $_FILES['csvfile']['tmp_name'];
$fileName = basename($_FILES['csvfile']['name']);
$fileSize = $_FILES['csvfile']['size'];
$fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

// Проверка расширения
if ($fileExt !== 'csv') {
    die("Ошибка: разрешены только CSV файлы.");
}

// Проверка размера
if ($fileSize > $maxFileSize) {
    die("Ошибка: файл слишком большой. Максимум 2 МБ.");
}

// Проверка имени (нет спецсимволов)
if (preg_match('/[()\[\]{}\/\\\]/', $fileName) || preg_match('/^\s/', $fileName)) {
    die("Ошибка: недопустимое имя файла.");
}

// Проверка структуры CSV (не менее 3 колонок)
$handle = fopen($fileTmp, "r");
$firstLine = fgetcsv($handle);
fclose($handle);

if (!$firstLine || count($firstLine) < 3) {
    die("Ошибка: CSV должен содержать хотя бы 3 столбца.");
}

// Сохраняем с уникальным именем
$newFileName = $uploadDir . time() . '_' . $fileName;
if (!move_uploaded_file($fileTmp, $newFileName)) {
    die("Ошибка: не удалось сохранить файл.");
}

// Перенаправляем на stats.php
header("Location: stats.php?file=" . urlencode($newFileName));
exit;
?>
