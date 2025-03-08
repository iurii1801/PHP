# Лабораторная работа №3. Массивы и Функции

## Цель работы

Освоить работу с массивами в PHP, применяя различные операции: создание, добавление, удаление, сортировка и поиск. Закрепить навыки работы с функциями, включая передачу аргументов, возвращаемые значения и анонимные функции.

---

## Задание 1. Работа с массивами

### Функционал:

- **Добавление, удаление, сортировка транзакций по дате или сумме.**  
- **Поиск транзакций по описанию или идентификатору.**  
- **Вывод списка транзакций в HTML-таблице.**  

### 1.1 Подготовка среды

- **Убедитесь, что установлен PHP 8+**  
- **Создайте файл `index.php`**  
- **Добавьте строгую типизацию:**

 ```php
   <?php

   declare(strict_types=1);
   ```

### 1.2 Создание массива транзакций

**Пример массива**:

```php
$transactions = [
    [
        "id" => 1,
        "date" => "2019-01-01",
        "amount" => 100.00,
        "description" => "Payment for groceries",
        "merchant" => "SuperMart",
    ],
    [
        "id" => 2,
        "date" => "2020-02-15",
        "amount" => 75.50,
        "description" => "Dinner with friends",
        "merchant" => "Local Restaurant",
    ],
];
```

### 1.3. Вывод списка транзакций

```php
<table border='1'>
<thead>
    <tr>
        <th>ID</th>
        <th>Date</th>
        <th>Amount</th>
        <th>Description</th>
        <th>Merchant</th>
        <th>Days Since</th>
    </tr>
</thead>
<tbody>
<?php foreach ($transactions as $t): ?>
    <tr>
        <td><?php echo $t['id']; ?></td>
        <td><?php echo $t['date']; ?></td>
        <td><?php echo number_format($t['amount'], 2); ?></td>
        <td><?php echo $t['description']; ?></td>
        <td><?php echo $t['merchant']; ?></td>
        <td><?php echo daysSinceTransaction($t['date']); ?></td>
    </tr>
<?php endforeach; ?>
</tbody>
</table>
```

### 1.4 Реализация функций

```php
/**
 * Вычисляет общую сумму всех транзакций.
 */
function calculateTotalAmount(array $transactions): float {
    return array_sum(array_column($transactions, 'amount'));
}

/**
 * Ищет транзакцию по части описания.
 */
function findTransactionByDescription(string $desc) {
    global $transactions;
    return array_filter($transactions, fn($t) => strpos($t['description'], $desc) !== false);
}

/**
 * Ищет транзакцию по ID (с использованием foreach).
 */
function findTransactionById(int $id) {
    global $transactions;
    foreach ($transactions as $t) {
        if ($t['id'] === $id) {
            return $t;
        }
    }
    return null;
}

/**
 * Ищет транзакцию по ID (с использованием array_filter).
 */
function findTransactionByIdFiltered(int $id) {
    global $transactions;
    $filtered = array_filter($transactions, fn($t) => $t['id'] === $id);
    return $filtered ? array_values($filtered)[0] : null;
}

/**
 * Возвращает количество дней между датой транзакции и текущей датой.
 */
function daysSinceTransaction(string $date): int {
    $transactionDate = new DateTime($date);
    $now = new DateTime();
    return $transactionDate->diff($now)->days;
}

/**
 * Добавляет новую транзакцию в массив.
 */
function addTransaction(int $id, string $date, float $amount, string $desc, string $merchant): void {
    global $transactions;
    $transactions[] = ["id" => $id, "date" => $date, "amount" => $amount, "description" => $desc, "merchant" => $merchant];
}
```

### 1.5. Сортировка транзакций

```php
// Сортировка по дате (по возрастанию) с использованием DateTime
usort($transactions, fn($a, $b) => (new DateTime($a['date'])) <=> (new DateTime($b['date'])));
```

```php
// Сортировка по сумме (по убыванию)
usort($transactions, fn($a, $b) => $b['amount'] - $a['amount']);
?>
```