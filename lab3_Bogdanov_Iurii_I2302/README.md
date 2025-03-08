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

#### Краткое пояснение

- `declare(strict_types=1);` включает строгую типизацию в PHP, что помогает избежать ошибок, связанных с неправильными типами данных.

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

#### Краткое пояснение

- Каждая транзакция представлена в виде ассоциативного массива, содержащего ID, дату, сумму, описание и название магазина.
- Даты хранятся в формате `YYYY-MM-DD`, что удобно для работы с `DateTime`.

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

#### Краткое пояснение

- Используется `foreach` для вывода массива транзакций в таблицу.
- Функция `number_format()` форматирует суммы до 2 знаков после запятой.
- Функция `daysSinceTransaction()` рассчитывает количество дней с момента транзакции.

### 1.4 Реализация функций

```php
/**
 * Вычисляет общую сумму всех транзакций.
 */
function calculateTotalAmount(array $transactions): float {
    return array_sum(array_column($transactions, 'amount'));
}
```

#### Краткое пояснение

- `array_column($transactions, 'amount')` — извлекает из массива `transactions` только значения amount (суммы).
- `array_sum(...)` — суммирует все значения и возвращает итоговую сумму.
- Функция возвращает число `float`, так как сумма может содержать копейки.

#### Пример использования:

```php
$total = calculateTotalAmount($transactions);
echo "Total amount: $total";
```

---

```php
/**
 * Ищет транзакцию по части описания.
 */
function findTransactionByDescription(string $desc) {
    global $transactions;
    return array_filter($transactions, fn($t) => strpos($t['description'], $desc) !== false);
}
```

#### Краткое пояснение

- Использует `array_filter()`, чтобы оставить только те транзакции, у которых `description` содержит `$desc`.
- Функция `strpos()` проверяет, есть ли `$desc` внутри `description`.
- Если совпадение найдено — транзакция добавляется в результат.

#### Пример использования:

```php
$result = findTransactionByDescription("Dinner");
print_r($result);
```

---

```php
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
```

#### Краткое пояснение

- Перебирает массив `foreach` и сравнивает `id` каждой транзакции с переданным значением.
- Если найдено совпадение, функция немедленно возвращает найденную транзакцию.
- Если транзакция не найдена, возвращает `null`.

#### Пример использования:

```php
$transaction = findTransactionById(1);
print_r($transaction);
```

---

```php
/**
 * Ищет транзакцию по ID (с использованием array_filter).
 */
function findTransactionByIdFiltered(int $id) {
    global $transactions;
    $filtered = array_filter($transactions, fn($t) => $t['id'] === $id);
    return $filtered ? array_values($filtered)[0] : null;
}
```

#### Краткое пояснение

- Фильтрует массив с помощью `array_filter()`, оставляя только те элементы, у которых `id` совпадает.
- Если хотя бы одна транзакция найдена, возвращает первый элемент (через `array_values()`), иначе `null`.

#### Пример использования:

```php
$transaction = findTransactionByIdFiltered(2);
print_r($transaction);
```

---

```php
/**
 * Возвращает количество дней между датой транзакции и текущей датой.
 */
function daysSinceTransaction(string $date): int {
    $transactionDate = new DateTime($date);
    $now = new DateTime();
    return $transactionDate->diff($now)->days;
}
```

#### Краткое пояснение

- оздает объект `DateTime` для переданной даты транзакции (`$date`).
- Создает второй объект DateTime для текущей даты (`now`).
- Вычисляет разницу в днях между этими датами (`->diff()->days`).
- Возвращает `int` — количество дней, прошедших с момента транзакции.

#### Пример использования:

```php
$days = daysSinceTransaction("2020-02-15");
echo "Days since transaction: $days";
```

---

```php
/**
 * Добавляет новую транзакцию в массив.
 */
function addTransaction(int $id, string $date, float $amount, string $desc, string $merchant): void {
    global $transactions;
    $transactions[] = ["id" => $id, "date" => $date, "amount" => $amount, "description" => $desc, "merchant" => $merchant];
}
```

#### Краткое пояснение

- Использует `global $transactions;`, чтобы изменить массив на глобальном уровне.
- Добавляет новый элемент в массив `$transactions`, содержащий **ID, дату, сумму, описание и название магазина**.
- Функция ничего не возвращает (`void`), просто добавляет новую транзакцию.

#### Пример использования:

```php
addTransaction(3, "2024-03-08", 250.75, "Car Repair", "Auto Service");
print_r($transactions);
```

---

### 1.5. Сортировка транзакций

```php
// Сортировка по дате (по возрастанию) с использованием DateTime
usort($transactions, fn($a, $b) => (new DateTime($a['date'])) <=> (new DateTime($b['date'])));
```

#### Краткое пояснение

- `usort()` — сортирует массив `$transactions`.
- Создаются объекты `DateTime` из дат транзакций (`new DateTime($a['date'])`).
- Используется оператор `<=>` (spaceship operator), который сравнивает две даты.

**Как работает сортировка?**

1. Дата "2019-01-01" станет перед "2020-02-15".
2. Старые транзакции будут вверху, новые — внизу.

```php
// Сортировка по сумме (по убыванию)
usort($transactions, fn($a, $b) => $b['amount'] - $a['amount']);
?>
```
#### Краткое пояснение

- `usort()` сортирует массив `$transactions` по убыванию `amount`.
- Сравнивает суммы транзакций (`$b['amount'] - $a['amount']`).
- Наибольшая сумма окажется вверху списка.

**Как работает сортировка?**

1. Транзакция с amount = 100.00 идет перед amount = 75.50.
2. Сортировка по убыванию — большие суммы вверху, маленькие внизу.


