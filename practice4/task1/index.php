<?php

//Задание 1 

// Загрузка данных из JSON-файла
$studentsFile = 'students.json';
$students = file_exists($studentsFile) ? json_decode(file_get_contents($studentsFile), true) : [];

// === Добавление студента ===
if (isset($_POST['action']) && $_POST['action'] === 'add') {
    $newStudent = [
        'id' => time(), // уникальный ID на основе времени
        'firstName' => $_POST['firstName'],
        'lastName' => $_POST['lastName'],
        'major' => $_POST['major'],
        'group' => $_POST['group'],
        'average_score' => (float)$_POST['average_score']
    ];
    $students[] = $newStudent;
    file_put_contents($studentsFile, json_encode($students, JSON_PRETTY_PRINT));
    header("Location: index.php");
    exit;
}

// === Удаление студента ===
if (isset($_GET['delete_id'])) {
    $students = array_filter($students, fn($s) => $s['id'] != $_GET['delete_id']);
    file_put_contents($studentsFile, json_encode(array_values($students), JSON_PRETTY_PRINT));
    header("Location: index.php");
    exit;
}

// === Поиск, фильтрация и сортировка ===
$search = $_GET['search'] ?? '';
$filterMajor = $_GET['filter_major'] ?? '';
$filterGroup = $_GET['filter_group'] ?? '';
$sort = $_GET['sort'] ?? '';

$filtered = $students;

// Фильтрация по фамилии
if ($search) {
    $filtered = array_filter($filtered, fn($s) => stripos($s['lastName'], $search) !== false);
}

// Фильтрация по специальности
if ($filterMajor) {
    $filtered = array_filter($filtered, fn($s) => $s['major'] === $filterMajor);
}

// Фильтрация по группе
if ($filterGroup) {
    $filtered = array_filter($filtered, fn($s) => $s['group'] === $filterGroup);
}

// Сортировка
if ($sort === 'score') {
    usort($filtered, fn($a, $b) => $b['average_score'] <=> $a['average_score']);
}

// Расчёт общего среднего балла
$totalScore = array_sum(array_column($students, 'average_score'));
$averageAll = count($students) > 0 ? round($totalScore / count($students), 2) : 0;

// Сбор уникальных значений для фильтров
$majors = array_unique(array_column($students, 'major'));
$groups = array_unique(array_column($students, 'group'));

?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Список студентов</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; }
        table { border-collapse: collapse; width: 100%; margin-top: 20px; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
        form { margin-bottom: 20px; }
        h2 { margin-top: 40px; }
    </style>
</head>
<body>

<h1>Система управления студентами</h1>

<!-- Поиск и фильтры -->
<form method="get">
    <input type="text" name="search" placeholder="Поиск по фамилии" value="<?= htmlspecialchars($search) ?>">
    
    <select name="filter_major">
        <option value="">-- Фильтр по специальности --</option>
        <?php foreach ($majors as $major): ?>
            <option value="<?= $major ?>" <?= $filterMajor === $major ? 'selected' : '' ?>><?= $major ?></option>
        <?php endforeach; ?>
    </select>

    <select name="filter_group">
        <option value="">-- Фильтр по группе --</option>
        <?php foreach ($groups as $group): ?>
            <option value="<?= $group ?>" <?= $filterGroup === $group ? 'selected' : '' ?>><?= $group ?></option>
        <?php endforeach; ?>
    </select>

    <button type="submit">Применить</button>
    <a href="index.php">Сброс</a>
</form>

<!-- Кнопка сортировки -->
<a href="?sort=score">Сортировать по среднему баллу</a>

<!-- Таблица студентов -->
<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Имя</th>
            <th>Фамилия</th>
            <th>Специальность</th>
            <th>Группа</th>
            <th>Средний балл</th>
            <th>Действия</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($filtered as $s): ?>
            <tr>
                <td><?= $s['id'] ?></td>
                <td><?= htmlspecialchars($s['firstName']) ?></td>
                <td><?= htmlspecialchars($s['lastName']) ?></td>
                <td><?= htmlspecialchars($s['major']) ?></td>
                <td><?= htmlspecialchars($s['group']) ?></td>
                <td><?= $s['average_score'] ?></td>
                <td><a href="?delete_id=<?= $s['id'] ?>" onclick="return confirm('Удалить студента?')">Удалить</a></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<p><strong>Общий средний балл:</strong> <?= $averageAll ?></p>

<!-- Форма добавления -->
<h2>Добавить нового студента</h2>
<form method="post">
    <input type="hidden" name="action" value="add">
    <p><input name="firstName" required placeholder="Имя"></p>
    <p><input name="lastName" required placeholder="Фамилия"></p>
    <p><input name="major" required placeholder="Специальность"></p>
    <p><input name="group" required placeholder="Группа"></p>
    <p><input name="average_score" required type="number" step="0.1" placeholder="Средний балл"></p>
    <button type="submit">Добавить</button>
</form>

</body>
</html>
