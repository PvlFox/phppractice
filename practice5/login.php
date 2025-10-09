<?php
// логин и пароль
$correctLogin = "admin";
$correctPassword = "Admin@123";

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login = trim($_POST['login'] ?? '');
    $password = $_POST['password'] ?? '';

    // Проверка на пустые поля
    if ($login === '' || $password === '') {
        $errors[] = "Заполните все поля.";
    }

    // Валидация логина
    if (!filter_var($login, FILTER_VALIDATE_EMAIL)) {
        if (!preg_match("/^[a-zA-Z0-9_]+$/", $login)) {
            $errors[] = "Логин должен быть email или содержать только буквы, цифры и _";
        }
    }

    // Валидация пароля
    if (strlen($password) < 8) {
        $errors[] = "Пароль должен содержать минимум 8 символов.";
    }
    if (!preg_match('/[A-Z]/', $password)) {
        $errors[] = "Пароль должен содержать хотя бы одну заглавную букву.";
    }
    if (!preg_match('/[0-9]/', $password)) {
        $errors[] = "Пароль должен содержать хотя бы одну цифру.";
    }
    if (!preg_match('/[!@#$%^&*()\-_=+{};:,<.>]/', $password)) {
        $errors[] = "Пароль должен содержать хотя бы один специальный символ (!@#$%^&*()-_=+ и т.д.).";
    }

    // Если ошибок нет, проверяем совпадение с эталонными данными
    if (empty($errors)) {
        if ($login === $correctLogin && $password === $correctPassword) {
            // Успешный вход — редирект
            header("Location: welcome.php?user=" . urlencode($login));
            exit;
        } else {
            $errors[] = "Неверный логин или пароль.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8" />
    <title>Авторизация - Ошибка</title>
    <link rel="stylesheet" href="style.css" />
</head>
<body>
    <div class="login-container">
        <h2>Ошибка авторизации</h2>

        <?php if (!empty($errors)): ?>
            <ul style="color: red;">
                <?php foreach ($errors as $error): ?>
                    <li><?=htmlspecialchars($error)?></li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>

        <a href="index.php">Вернуться назад</a>
    </div>
</body>
</html>
