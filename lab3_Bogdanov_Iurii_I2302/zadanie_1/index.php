<?php
declare(strict_types=1);

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

/**
 * Вычисляет общую сумму всех транзакций.
 *
 * @param array $transactions Массив транзакций
 * @return float Общая сумма всех транзакций
 */
function calculateTotalAmount(array $transactions): float {
    return array_sum(array_column($transactions, 'amount'));
}

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

// Сортировка по дате (по возрастанию) с использованием DateTime
usort($transactions, fn($a, $b) => (new DateTime($a['date'])) <=> (new DateTime($b['date'])));

// Сортировка по сумме (по убыванию)
usort($transactions, fn($a, $b) => $b['amount'] - $a['amount']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Transaction List</title>
    <style>
        table { border-collapse: collapse; width: 60%; }
        th, td { border: 1px solid black; padding: 8px; text-align: center; }
    </style>
</head>
<body>

<h2>Transaction List</h2>

<table>
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
        <?php foreach ($transactions as $t) { ?>
            <tr>
                <td><?php echo $t['id']; ?></td>
                <td><?php echo $t['date']; ?></td>
                <td><?php echo number_format($t['amount'], 2); ?></td>
                <td><?php echo $t['description']; ?></td>
                <td><?php echo $t['merchant']; ?></td>
                <td><?php echo daysSinceTransaction($t['date']); ?></td>
            </tr>
        <?php } ?>
    </tbody>
</table>

<p><strong>Total Amount: <?php echo number_format(calculateTotalAmount($transactions), 2); ?></strong></p>

</body>
</html>
