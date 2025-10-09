<?php
// Задание 5

$emails = "user@domain.com, ab@x.y, user@domain, @domain.com, user@.com, john.doe@company.com";
$emailList = explode(", ", $emails);

$total = count($emailList);
$validCount = 0;
$invalidList = [];

echo "Общий анализ:\n";
echo "Всего email-адресов: $total\n";

foreach ($emailList as $email) {
    $result = validateEmail($email);

    if ($result === true) {
        $validCount++;
        analyzeEmail($email);
    } else {
        $invalidList[] = "$email — Невалиден: $result";
    }
}

echo "Валидных: $validCount\n";
echo "Невалидных: " . ($total - $validCount) . "\n";
echo "---\n";
echo "Список невалидных email-адресов:\n";
foreach ($invalidList as $line) {
    echo $line . PHP_EOL;
}

// Валидация Email 
function validateEmail($email) {
    $atCount = substr_count($email, '@');
    if ($atCount !== 1) return "Неверное количество символов '@'";

    [$login, $domain] = explode('@', $email);
    if (strlen($login) < 3) return "Имя пользователя слишком короткое";
    if (strlen($domain) < 5) return "Домен слишком короткий";
    if (strpos($domain, '.') === false) return "Домен не содержит точку";
    if ($email[0] === '.' || substr($email, -1) === '.') return "Email начинается или заканчивается точкой";

    return true;
}

// Анализ валидного Email 
function analyzeEmail($email) {
    [$login, $domain] = explode('@', $email);

    // Короткое имя
    if (strpos($login, '.') !== false) {
        $short = explode('.', $login)[0];
    } elseif (strlen($login) > 8) {
        $short = substr($login, 0, 6);
    } else {
        $short = $login;
    }

    // Тип домена
    if (str_ends_with($domain, '.com')) {
        $type = "Коммерческий";
    } elseif (str_ends_with($domain, '.org')) {
        $type = "Некоммерческий";
    } elseif (str_ends_with($domain, '.net')) {
        $type = "Сетевой";
    } else {
        $type = "Другой";
    }

    echo "Логин: $login, Короткое имя: $short, Домен: $domain, Тип: $type\n";
}

?>
