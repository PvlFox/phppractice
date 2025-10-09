<?php

// Задание 2

function validatePassword($password) {
    echo "Проверка пароля:\n";

    // Проверка длины
    if (mb_strlen($password) >= 8) {
        echo "Длина пароля: ок\n";
        $lengthOk = true;
    } else {
        echo "Длина пароля: менее 8 символов.\n";
        $lengthOk = false;
    }

    // Заглавные буквы (латиница и кириллица)
    if (preg_match('/[A-ZА-Я]/u', $password)) {
        echo "Заглавные буквы: присутствуют.\n";
        $upperOk = true;
    } else {
        echo "Заглавные буквы: отсутствуют.\n";
        $upperOk = false;
    }

    // Строчные буквы (латиница и кириллица)
    if (preg_match('/[a-zа-я]/u', $password)) {
        echo "Строчные буквы: присутствуют.\n";
        $lowerOk = true;
    } else {
        echo "Строчные буквы: отсутствуют.\n";
        $lowerOk = false;
    }

    // Цифры
    if (preg_match('/\d/u', $password)) {
        echo "Цифры: присутствуют.\n";
        $digitOk = true;
    } else {
        echo "Цифры: отсутствуют.\n";
        $digitOk = false;
    }

    $isValid = $lengthOk && $upperOk && $lowerOk && $digitOk;

    echo "Результат проверки: " . ($isValid ? "Пароль надёжен" : "Пароль ненадёжен") . "\n";

    return $isValid;
}

// Пример
$isValid = validatePassword("Qwertyuiop12345");
?>
