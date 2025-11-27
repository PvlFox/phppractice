<?php
require 'db.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $group = $_POST['group_name'] ?? '';
    $rating = $_POST['rating'] ?? '';

    if ($name && $group && $rating !== '') {
        $stmt = $pdo->prepare("INSERT INTO students (name, group_name, rating) VALUES (:name, :group, :rating)");
        $stmt->execute(['name' => $name, 'group' => $group, 'rating' => $rating]);
        $message = "Студент добавлен!";
    } else {
        $message = "Заполните все поля!";
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Добавить студента</title>
    <style>
        form { width: 300px; margin: 50px auto; display: flex; flex-direction: column; }
        input { margin-bottom: 10px; padding: 5px; }
        button { padding: 5px; }
        p.message { text-align: center; color: green; }
    </style>
</head>
<body>
    <h2 style="text-align:center;">Добавить студента</h2>

    <?php if($message): ?>
        <p class="message"><?= htmlspecialchars($message) ?></p>
        <?php if($message === "Студент добавлен!"): ?>
            <p style="text-align:center;"><a href="index.php">Вернуться к списку</a></p>
        <?php endif; ?>
    <?php endif; ?>

    <form method="post">
        <input type="text" name="name" placeholder="Имя" required>
        <input type="text" name="group_name" placeholder="Группа" required>
        <input type="number" step="0.01" name="rating" placeholder="Рейтинг" required>
        <button type="submit">Добавить</button>
    </form>
</body>
</html>
