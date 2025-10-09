<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8" />
    <title>Форма авторизации</title>
    <link rel="stylesheet" href="style.css" />
</head>
<body>
    <div class="login-container">
        <h2>Вход в систему</h2>
        <form action="login.php" method="post">
            <label for="login">Логин (email или username):</label>
            <input type="text" id="login" name="login" required />

            <label for="password">Пароль:</label>
            <input type="password" id="password" name="password" required />

            <button type="submit">Войти</button>
        </form>
    </div>
</body>
</html>
