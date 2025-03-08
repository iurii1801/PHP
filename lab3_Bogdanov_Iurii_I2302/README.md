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

**Функция `calculateTotalAmount`**

```php
/**
 * Вычисляет общую сумму всех транзакций.
 *
 * @param array $transactions Массив транзакций
 * @return float Общая сумма всех транзакций
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

**Функция `findTransactionByDescription`**

```php
/**
 * Ищет транзакцию по части описания.
 *
 * @param string $desc Часть описания
 * @return array|null Найденная транзакция или null, если не найдена
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

**Функция `findTransactionById`**

```php
/**
 * Ищет транзакцию по ID (с использованием foreach).
 *
 * @param int $id ID транзакции
 * @return array|null Найденная транзакция или null, если не найдена
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

**Функция `findTransactionByIdFiltered`**

```php
/**
 * Ищет транзакцию по ID (с использованием array_filter).
 *
 * @param int $id ID транзакции
 * @return array|null Найденная транзакция или null, если не найдена
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

**Функция `daysSinceTransaction`**

```php
/**
 * Возвращает количество дней между датой транзакции и текущей датой.
 *
 * @param string $date Дата транзакции в формате YYYY-MM-DD
 * @return int Количество дней с момента транзакции
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

**Функция `addTransaction`**

```php
/**
 * Добавляет новую транзакцию в массив.
 *
 * @param int $id ID транзакции
 * @param string $date Дата транзакции (YYYY-MM-DD)
 * @param float $amount Сумма транзакции
 * @param string $desc Описание транзакции
 * @param string $merchant Магазин, получивший платеж
 * @return void
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

---

## Результат
![image](https://i.imgur.com/6Z1A5ot.png)

## Задание 2. Работа с файловой системой

## Функционал:  

- **Создание директории `image/` и загрузка в нее изображений.**  
- **Сканирование содержимого папки и получение списка файлов `.jpg`.**  
- **Вывод изображений в виде галереи на веб-странице.**  

---

## 2.1 Подготовка среды  

1. **Создайте папку `image/` в корневом каталоге проекта.**  
2. **Добавьте в папку `image/` не менее 20-30 изображений в формате `.jpg`.**  
3. **Создайте файл `index.php` в папке `zadanie_2/`.**  
4. **Создайте файл `styles.css` для стилизации галереи.**  

---

## 2.2 Код `index.php` – Вывод галереи изображений  

```php
<?php
declare(strict_types=1);

/**
 * Получает список изображений формата .jpg из указанной директории.
 *
 * @param string $dir Путь к папке с изображениями
 * @return array Массив имен файлов изображений
 */
function getImages(string $dir): array {
    $files = scandir($dir);
    if ($files === false) {
        return [];
    }
    return array_filter($files, fn($file) => is_file($dir . $file) && pathinfo($file, PATHINFO_EXTENSION) === 'jpg');
}

$dir = 'image/';
$images = getImages($dir);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Car Gallery</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

<div class="header">
    <nav class="menu">
        <a href="#">Supercars</a> | 
        <a href="#">News</a> | 
        <a href="#">Contacts</a>
    </nav>
</div>

<h2>#cars</h2>
<p>Explore the world of supercars</p>

<div class="gallery">
    <?php foreach ($images as $image): ?>
        <img src="<?php echo $dir . $image; ?>" alt="Car Image">
    <?php endforeach; ?>
</div>

<div class="footer">
    <p>Supercars Gallery © 2024</p>
</div>

</body>
</html>
```

---

 **Функция `getImages()`** – Получение списка изображений

```php
/**
 * Получает список изображений формата .jpg из указанной директории.
 *
 * @param string $dir Путь к папке с изображениями
 * @return array Массив имен файлов изображений
 */
function getImages(string $dir): array {
    $files = scandir($dir);
    if ($files === false) {
        return [];
    }
    return array_filter($files, fn($file) => is_file($dir . $file) && pathinfo($file, PATHINFO_EXTENSION) === 'jpg');
}
```

#### Краткое пояснение 

- `scandir($dir)` получает список всех файлов в папке `image/`.
- Если функция не смогла получить файлы (`false`), возвращает пустой массив [].
- Фильтрует список, оставляя только файлы с расширением `.jpg`.

#### Пример использования:

```php
$images = getImages('image/');
print_r($images); // Выведет список всех изображений .jpg
```

---

## Результат

![image](https://i.imgur.com/Sg1M16h.png)

---

## Контрольные вопросы

**1. Что такое массивы в PHP?**

- Массив – это структура данных, которая позволяет хранить несколько значений в одной переменной. В PHP массивы динамические, что означает, что их размер изменяется автоматически при добавлении новых элементов.

#### Основные виды массивов:

1. Индексированные массивы – элементы хранятся по числовым индексам, начиная с 0.
2. Ассоциативные массивы – ключи представляют собой строки, а не числа.
3. Многомерные массивы – массивы, содержащие другие массивы.

**2. Каким образом можно создать массив в PHP?**

В PHP массив можно создать несколькими способами:

1. Использование квадратных скобок `[]` (современный вариант).
2. Использование `array()` (старый вариант, поддерживается в старых версиях PHP).
3. Создание пустого массива и добавление элементов вручную.

**3. Для чего используется цикл foreach?**

Цикл foreach в PHP предназначен для простого перебора элементов массива.

- Почему foreach удобен?

1. Не требует указания длины массива (как for).
2. Автоматически перебирает все элементы.
3. Позволяет работать как с индексированными, так и с ассоциативными массивами.

- Где используется?

1. Вывод списка товаров на сайте.
2. Обработка результатов SQL-запросов.
3. Чтение данных из API (JSON, XML).

`foreach` не изменяет сам массив, он только считывает значения. 

---

## Вывод:

В ходе лабораторной работы изучены массивы в PHP и их обработка: добавление, удаление, сортировка и поиск элементов. Реализована система управления банковскими транзакциями с расчетом общей суммы, поиском по ID и описанию, а также сортировкой данных. Использован `DateTime` для работы с датами.  

Также создана галерея изображений, загружающая `.jpg` файлы из папки и отображающая их с CSS-анимацией. Закреплены навыки работы с `scandir()`, `array_filter()`, `usort()`, `foreach` и другими функциями PHP, применимыми в веб-разработке.

## Библиография:

1. Официальная документация PHP: [https://www.php.net/manual/ru/](https://www.php.net/manual/ru/function.usort.php)
2. Работа с массивами в PHP: [https://www.php.net/manual/ru/language.types.array.php](https://www.php.net/manual/ru/language.types.array.php)
3. Функция foreach в PHP: [https://www.php.net/manual/ru/control-structures.foreach.php](https://www.php.net/manual/ru/control-structures.foreach.php)
4. Работа с файлами и директориями (`scandir()`): [https://www.php.net/manual/ru/function.scandir.php](https://www.php.net/manual/ru/function.scandir.php)
5. Сортировка массивов (`usort()`): [https://www.php.net/manual/ru/function.usort.php](https://www.php.net/manual/ru/function.usort.php)