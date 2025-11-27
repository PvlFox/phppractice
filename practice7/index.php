<?php
require 'db.php';

$searchTerm = $_GET['search'] ?? '';

if ($searchTerm) {
    $stmt = $pdo->prepare("SELECT * FROM students WHERE name LIKE :search");
    $stmt->execute(['search' => "%$searchTerm%"]);
} else {
    $stmt = $pdo->query("SELECT * FROM students");
}

$students = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Список студентов</title>
    <style>
        table { border-collapse: collapse; width: 80%; margin: auto; }
        th, td { border: 1px solid #000; padding: 8px; text-align: center; }
        th { background-color: #eee; }
        form { text-align: center; margin-bottom: 20px; }
    </style>
</head>
<body>
    <h2 style="text-align:center;">Список студентов</h2>

    <form method="get">
        <input type="text" name="search" placeholder="Поиск по имени" value="<?= htmlspecialchars($searchTerm) ?>">
        <button type="submit">Найти</button>
        <a href="index.php">Сброс</a>
    </form>

    <table>
        <tr>
            <th>ID</th>
            <th>Имя</th>
            <th>Группа</th>
            <th>Рейтинг</th>
            <th>Действия</th>
        </tr>
        <?php foreach ($students as $row): ?>
        <tr>
            <td><?= htmlspecialchars($row['id']) ?></td>
            <td><?= htmlspecialchars($row['name']) ?></td>
            <td><?= htmlspecialchars($row['group_name']) ?></td>
            <td><?= htmlspecialchars($row['rating']) ?></td>
            <td><a href="delete.php?id=<?= $row['id'] ?>">Удалить</a></td>
        </tr>
        <?php endforeach; ?>
    </table>

    <p style="text-align:center;"><a href="add_student.php">Добавить студента</a></p>
</body>
</html>
