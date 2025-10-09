<?php
$user = $_GET['user'] ?? '';
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8" />
    <title>Добро пожаловать</title>
    <link rel="stylesheet" href="style.css" />
</head>
<body>
    <div class="login-container">
        <h2>Добро пожаловать, <?=htmlspecialchars($user)?>!</h2>
        <p>Вы успешно вошли в систему.</p>
        <a href="index.php">Выйти</a>
    </div>
</body>
</html>
