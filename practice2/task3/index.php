<?php
// Задание 3

$a1 = 3;
$d = 4;
$n = 15;
$sum = 0;
$evenCount = 0;

echo "Последовательность: ";

for ($i = 0; $i < $n; $i++) {
    $term = $a1 + $i * $d;
    echo "$term ";
    $sum += $term;
    if ($term % 2 == 0) {
        $evenCount++;
    }
}

echo PHP_EOL;
echo "Сумма элементов: $sum" . PHP_EOL;
echo "Количество четных элементов: $evenCount" . PHP_EOL;
?>
