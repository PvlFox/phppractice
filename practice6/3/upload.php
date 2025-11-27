<?php
$maxFileSize = 2 * 1024 * 1024;

$uploadDir = __DIR__ . '/uploads/';

if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

if (!isset($_FILES['csvfile']) || $_FILES['csvfile']['error'] !== UPLOAD_ERR_OK) {
    die("Ошибка: файл не выбран или возникла ошибка при загрузке.");
}

$fileTmp = $_FILES['csvfile']['tmp_name'];
$fileName = basename($_FILES['csvfile']['name']);
$fileSize = $_FILES['csvfile']['size'];
$fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

if ($fileExt !== 'csv') {
    die("Ошибка: разрешены только CSV файлы.");
}

if ($fileSize > $maxFileSize) {
    die("Ошибка: файл слишком большой. Максимум 2 МБ.");
}

if (preg_match('/[()\[\]{}\/\\\]/', $fileName) || preg_match('/^\s/', $fileName)) {
    die("Ошибка: недопустимое имя файла.");
}

$handle = fopen($fileTmp, "r");
$firstLine = fgetcsv($handle);
fclose($handle);

if (!$firstLine || count($firstLine) < 3) {
    die("Ошибка: CSV должен содержать хотя бы 3 столбца.");
}

$newFileName = $uploadDir . time() . '_' . $fileName;
if (!move_uploaded_file($fileTmp, $newFileName)) {
    die("Ошибка: не удалось сохранить файл.");
}

header("Location: stats.php?file=" . urlencode($newFileName));
exit;
?>
