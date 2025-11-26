<?php
function filterLogs($filename, $method) {
    if (!file_exists($filename)) {
        return "Файл '$filename' не найден!";
    }

    $outputFile = "logs_" . strtoupper($method) . ".logs";

    $fp = fopen($filename, "r");
    if (!$fp) return "Не удалось открыть файл '$filename' для чтения.";

    $fpOut = fopen($outputFile, "w");
    if (!$fpOut) {
        fclose($fp);
        return "Не удалось создать файл '$outputFile'.";
    }

    $lineCount = 0;

    while (!feof($fp)) {
        $line = fgets($fp);
        if ($line !== false && strpos($line, $method) !== false) {
            fwrite($fpOut, $line);
            $lineCount++;
        }
    }

    fclose($fp);
    fclose($fpOut);

    return "Файл [$outputFile] был создан. В нём содержится $lineCount строк.";
}

// --- Обработка формы ---
$message = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $method = strtoupper($_POST['method'] ?? '');
    if (in_array($method, ['GET', 'POST'])) {
        $message = filterLogs("src/logs/access.log", $method);
    } else {
        $message = "Неверный метод! Выберите GET или POST.";
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Фильтрация логов по HTTP-методу</title>
</head>
<body>
    <h2>Фильтрация логов по HTTP-методу</h2>

    <form method="post">
        <label>Выберите метод:
            <select name="method">
                <option value="GET">GET</option>
                <option value="POST">POST</option>
            </select>
        </label>
        <button type="submit">Фильтровать</button>
    </form>

    <?php if ($message): ?>
        <p><strong><?php echo htmlspecialchars($message); ?></strong></p>
    <?php endif; ?>
</body>
</html>
