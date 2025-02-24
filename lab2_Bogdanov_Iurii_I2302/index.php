<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Расписание и Циклы</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

<h1>Расписание</h1>

<?php
$weekDay = date('l'); 

function getSchedule($weekDay, $days, $hours) {
    return in_array($weekDay, $days) ? $hours : 'Нерабочий день';
}

$employees = [
    ['1', 'John Styles', getSchedule($weekDay, ['Monday', 'Wednesday', 'Friday'], '8:00-12:00')],
    ['2', 'Jane Doe', getSchedule($weekDay, ['Tuesday', 'Thursday', 'Saturday'], '12:00-16:00')]
];
?>

<table>
    <tr>
        <th>№</th>
        <th>Фамилия Имя</th>
        <th>График работы</th>
    </tr>
    <?php foreach ($employees as $employee): ?>
        <tr>
            <?php foreach ($employee as $data): ?>
                <td><?= htmlspecialchars($data) ?></td>
            <?php endforeach; ?>
        </tr>
    <?php endforeach; ?>
</table>

<h1>Циклы</h1>

<div class="cycle-section">

    <h2>Цикл for</h2>

    <?php
    $a = 0;
    $b = 0;
    echo "<p>Initial values: a = $a, b = $b</p>";

    for ($i = 0; $i <= 5; $i++) {
        $a += 10;
        $b += 5;
        echo "<p>a = $a, b = $b</p>";
    }

    echo "<p><b>End of the loop: a = $a, b = $b</b></p>";
    ?>

</div>

<div class="cycle-section">

    <h2>Цикл while</h2>

    <?php
    $a = 0;
    $b = 0;
    $i = 0;
    echo "<p>Initial values: a = $a, b = $b</p>";

    while ($i <= 5) {
        $a += 10;
        $b += 5;
        echo "<p>a = $a, b = $b</p>";
        $i++;
    }

    echo "<p><b>End of the loop: a = $a, b = $b</b></p>";
    ?>

</div>

<div class="cycle-section">

    <h2>Цикл do-while</h2>

    <?php
    $a = 0;
    $b = 0;
    $i = 0;
    echo "<p>Initial values: a = $a, b = $b</p>";

    do {
        $a += 10;
        $b += 5;
        echo "<p>a = $a, b = $b</p>";
        $i++;
    } while ($i <= 5);

    echo "<p><b>End of the loop: a = $a, b = $b</b></p>";
    ?>

</div>

</body>
</html>
