<?php

//Задание 2

session_start();

// Инициализируем товары в сессии, чтобы изменения сохранялись между запросами
if (!isset($_SESSION['products'])) {
    $_SESSION['products'] = [
        ['product_id' => 101, 'name' => 'Laptop', 'price' => 85000, 'in_stock' => true],
        ['product_id' => 102, 'name' => 'Smartphone', 'price' => 15000, 'in_stock' => true],
        ['product_id' => 103, 'name' => 'Headphones', 'price' => 5000, 'in_stock' => false],
        ['product_id' => 104, 'name' => 'Monitor', 'price' => 32000, 'in_stock' => true],
        ['product_id' => 105, 'name' => 'Keyboard', 'price' => 4500, 'in_stock' => true],
        ['product_id' => 106, 'name' => 'Mouse', 'price' => 3000, 'in_stock' => false],
    ];
}

$products = &$_SESSION['products'];
$message = '';
$output = '';

function formatPrice($price) {
    return number_format($price, 0, '', ' ') . " тг.";
}

function checkProductAvailability($products, $product_id) {
    foreach ($products as $product) {
        if ($product['product_id'] == $product_id) {
            $status = $product['in_stock'] ? "В наличии" : "Нет в наличии";
            return "Товар найден: {$product['name']} ({$status})";
        }
    }
    return "Товар с ID $product_id не найден.";
}

function applyDiscount($products, $discountPercent) {
    $products_on_sale = [];
    foreach ($products as $product) {
        $discounted = $product;
        if ($product['in_stock']) {
            $discounted['price'] = round($product['price'] * (1 - $discountPercent / 100));
        }
        $products_on_sale[] = $discounted;
    }
    return $products_on_sale;
}

function getProductNames($products) {
    return array_column($products, 'name');
}

function sortProductsByPrice(&$products) {
    $prices = array_column($products, 'price');
    array_multisort($prices, SORT_ASC, $products);
}

// Обработка формы
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    switch ($action) {
        case 'check_availability':
            $id = intval($_POST['product_id'] ?? 0);
            if ($id > 0) {
                $message = checkProductAvailability($products, $id);
            } else {
                $message = "Введите корректный ID товара.";
            }
            break;

        case 'apply_discount':
            $discount = floatval($_POST['discount'] ?? 0);
            if ($discount > 0 && $discount <= 100) {
                $discounted = applyDiscount($products, $discount);
                $output .= "<h4>Список товаров со скидкой {$discount}%:</h4>";
                foreach ($discounted as $p) {
                    $output .= "ID: {$p['product_id']} | {$p['name']} | " . formatPrice($p['price']) . " | " . ($p['in_stock'] ? "В наличии" : "Нет в наличии") . "<br>";
                }
            } else {
                $message = "Введите корректный процент скидки (от 0 до 100).";
            }
            break;

        case 'list_names':
            $names = getProductNames($products);
            $output .= "<h4>Список названий товаров:</h4><ul>";
            foreach ($names as $name) {
                $output .= "<li>$name</li>";
            }
            $output .= "</ul>";
            break;

        case 'sort_price':
            sortProductsByPrice($products);
            $output .= "<h4>Товары, отсортированные по цене (возрастание):</h4>";
            foreach ($products as $p) {
                $output .= "ID: {$p['product_id']} | {$p['name']} | " . formatPrice($p['price']) . " | " . ($p['in_stock'] ? "В наличии" : "Нет в наличии") . "<br>";
            }
            break;

        default:
            $message = "Выберите действие.";
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8" />
    <title>Каталог товаров интернет-магазина</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 700px; margin: 20px auto; }
        h1 { color: #333; }
        form { margin-bottom: 20px; padding: 10px; border: 1px solid #ccc; }
        label { display: block; margin: 8px 0 4px; }
        input[type="text"], input[type="number"] { padding: 6px; width: 100%; max-width: 300px; }
        input[type="submit"] { margin-top: 10px; padding: 8px 12px; }
        .message { margin: 10px 0; color: green; }
        .error { color: red; }
    </style>
</head>
<body>

<h1>Каталог товаров интернет-магазина</h1>

<?php if ($message): ?>
    <div class="message"><?=htmlspecialchars($message)?></div>
<?php endif; ?>

<div>
    <form method="post">
        <input type="hidden" name="action" value="check_availability" />
        <label for="product_id">Проверить наличие товара по ID:</label>
        <input type="number" id="product_id" name="product_id" min="1" required />
        <input type="submit" value="Проверить" />
    </form>

    <form method="post">
        <input type="hidden" name="action" value="apply_discount" />
        <label for="discount">Применить скидку ко всем товарам в наличии (%):</label>
        <input type="number" id="discount" name="discount" min="0" max="100" step="0.1" required />
        <input type="submit" value="Применить скидку" />
    </form>

    <form method="post">
        <input type="hidden" name="action" value="list_names" />
        <input type="submit" value="Показать список названий товаров" />
    </form>

    <form method="post">
        <input type="hidden" name="action" value="sort_price" />
        <input type="submit" value="Отсортировать товары по цене (возрастание)" />
    </form>
</div>

<hr>

<div>
    <?= $output ?>
</div>

</body>
</html>
