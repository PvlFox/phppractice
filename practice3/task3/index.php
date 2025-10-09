<?php

// Задание 3

function analyzeLogForEmails($logText) {
    preg_match_all('/[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}/iu', $logText, $matches);

    $emails = $matches[0];
    $count = count($emails);

    if ($count > 0) {
        echo "Найденные email-адреса:\n";
        echo "------------------------\n";
        foreach ($emails as $index => $email) {
            echo ($index + 1) . ". " . $email . "\n";
        }
        echo "------------------------\n";
    } else {
        echo "Email-адресов не найдено.\n";
    }

    return $count;
}

// Пример
$logText = <<<LOG
[2023-11-15 09:23:45] ERROR: Login failed for user john.doe@example.com
[2023-11-15 09:24:12] SUCCESS: User admin@company.org logged in successfully
[2023-11-15 09:25:33] WARNING: Password reset requested for alice.smith@gmail.com
[2023-11-15 09:26:47] INFO: New registration from bob.johnson@mail.ru
[2023-11-15 09:27:19] ERROR: Invalid credentials for support@mycompany.com
[2023-11-15 09:28:05] DEBUG: Session started for user123@yahoo.com
[2023-11-15 09:29:27] NOTICE: Email sent to marketing@corporation.org
[2023-11-15 09:30:44] ERROR: Payment failed for client456@business.com
[2023-11-15 09:31:12] SUCCESS: Order confirmed for customer789@shop.ru
[2023-11-15 09:32:38] WARNING: Suspicious activity from hacker@danger.net
[2023-11-15 09:33:51] INFO: Contact form submission from info@website.com
[2023-11-15 09:34:22] ERROR: Database connection failed for dbadmin@server.local
[2023-11-15 09:35:47] SUCCESS: Backup completed to storage@cloud-service.io
[2023-11-15 09:36:13] DEBUG: API call from partner-api@external.com
[2023-11-15 09:37:29] WARNING: Invalid email format detected: not_an_email
[2023-11-15 09:38:45] ERROR: User not found with email: ghost@unknown.org
[2023-11-15 09:39:12] INFO: Newsletter sent to 1500 subscribers@list.com
[2023-11-15 09:40:33] SUCCESS: Password changed for security@protected.net
[2023-11-15 09:41:58] ERROR: Two-factor authentication failed for secure@account.com
[2023-11-15 09:42:24] DEBUG: Cache cleared for webmaster@site.com
LOG;

$count = analyzeLogForEmails($logText);
echo "Всего email-адресов: $count\n";
?>
