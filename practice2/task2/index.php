<?php
// Задание 2

$char = 'A';
$code = ord($char); 

echo "Код символа 'A': $code" . PHP_EOL;
echo "Символ с кодом (65 + 5): " . chr($code + 5) . PHP_EOL;
echo "Символ с кодом (97 - 2): " . chr(97 - 2) . PHP_EOL;
?>
