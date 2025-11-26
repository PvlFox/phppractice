<?php
if (!isset($_GET['file'])) {
    die("Файл не указан.");
}

$file = $_GET['file'];

if (!file_exists($file)) {
    die("Файл не найден.");
}

$csvData = [];
$handle = fopen($file, 'r');
$header = fgetcsv($handle);

while (($row = fgetcsv($handle)) !== false) {
    $csvData[] = array_combine($header, $row);
}
fclose($handle);

$fileSize = filesize($file);
$uploadDate = date("d.m.Y H:i:s", filemtime($file));
$numRows = count($csvData);
$numCols = count($header);

$emptyCells = 0;
$genres = [];
$directors = [];
$totalRuntime = 0;
$runtimeCount = 0;

foreach ($csvData as $row) {
    foreach ($row as $cell) {
        if (trim($cell) === '') $emptyCells++;
    }

    $rowGenres = explode(',', $row['genre']);
    foreach ($rowGenres as $g) {
        $genres[trim($g)] = ($genres[trim($g)] ?? 0) + 1;
    }

    $directors[$row['director']] = ($directors[$row['director']] ?? 0) + 1;

    if (preg_match('/(\d+)/', $row['runtime'], $matches)) {
        $totalRuntime += (int)$matches[1];
        $runtimeCount++;
    }
}

arsort($genres);
$topGenres = array_slice($genres, 0, 2, true);

arsort($directors);
$topDirector = array_key_first($directors);

$avgRuntime = $runtimeCount ? round($totalRuntime / $runtimeCount, 2) : 0;
?>

<h1>Статистика CSV-файла</h1>
<ul>
    <li>Исходное имя файла: <?=htmlspecialchars(basename($file))?></li>
    <li>Путь к файлу: <?=htmlspecialchars($file)?></li>
    <li>Размер файла: <?=round($fileSize/1024,2)?> KB</li>
    <li>Дата загрузки: <?=$uploadDate?></li>
    <li>Количество строк: <?=$numRows?></li>
    <li>Количество столбцов: <?=$numCols?></li>
    <li>Количество пустых ячеек: <?=$emptyCells?></li>
    <li>Два самых популярных жанра: <?=implode(', ', array_keys($topGenres))?></li>
    <li>Средняя продолжительность фильма: <?=$avgRuntime?> мин</li>
    <li>Самый популярный режиссёр: <?=$topDirector?></li>
</ul>

<h2>Фильтрация данных</h2>
<form method="get">
    <input type="hidden" name="file" value="<?=htmlspecialchars($file)?>">
    <label for="filter">Выберите фильтр:</label>
    <select name="filter" id="filter" onchange="this.form.submit()">
        <option value="">--Без фильтра--</option>
        <option value="long_under8" <?=($_GET['filter']??'')=='long_under8'?'selected':''?>>10 самых длительных фильмов с рейтингом ниже 8.0</option>
        <option value="sci_after2015" <?=($_GET['filter']??'')=='sci_after2015'?'selected':''?>>Фильмы после 2015 года в жанре Sci-Fi</option>
        <option value="old_lowgross" <?=($_GET['filter']??'')=='old_lowgross'?'selected':''?>>Фильмы до 1980 года с доходом меньше 10M</option>
    </select>
</form>

<?php
$filter = $_GET['filter'] ?? '';
$filtered = $csvData;

if ($filter === 'long_under8') {
    usort($filtered, function($a, $b){
        preg_match('/(\d+)/', $b['runtime'], $mb);
        preg_match('/(\d+)/', $a['runtime'], $ma);
        return ($mb[1]??0) - ($ma[1]??0);
    });
    $filtered = array_filter($filtered, fn($r) => (float)$r['rating'] < 8.0);
    $filtered = array_slice($filtered, 0, 10);
} elseif ($filter === 'sci_after2015') {
    $filtered = array_filter($filtered, function($r){
        return (int)trim($r['release_year'], '()') > 2015 && str_contains($r['genre'], 'Sci-Fi');
    });
} elseif ($filter === 'old_lowgross') {
    $filtered = array_filter($filtered, function($r){
        $year = (int)trim($r['release_year'], '()');
        $gross = (float)str_replace(['$', 'M'], '', $r['gross']);
        return $year <= 1980 && $gross < 10;
    });
}

if ($filter) {
    echo "<h3>Результаты фильтрации:</h3>";
    echo "<table border='1' cellpadding='5' cellspacing='0'>";
    echo "<tr><th>Title</th><th>Director</th><th>Year</th><th>Runtime</th><th>Rating</th></tr>";
    foreach ($filtered as $r) {
        echo "<tr>";
        echo "<td>".htmlspecialchars($r['title'])."</td>";
        echo "<td>".htmlspecialchars($r['director'])."</td>";
        echo "<td>".htmlspecialchars($r['release_year'])."</td>";
        echo "<td>".htmlspecialchars($r['runtime'])."</td>";
        echo "<td>".htmlspecialchars($r['rating'])."</td>";
        echo "</tr>";
    }
    echo "</table>";
}
?>
