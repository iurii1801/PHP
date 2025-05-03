<?php
/**
 * Файл: layout.php
 * Назначение: Общий макет (layout) для всех страниц проекта RepairTicketSystem.
 * Подключает стили, навигацию и вставляет контент конкретной страницы.
 *
 * @package RepairTicketSystem\Templates
 * @var string $templatePath Абсолютный путь к шаблону страницы, подставляется из функции render().
 */
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Система заявок на ремонт</title>
    <link rel="stylesheet" href="/repairTicketSystem/public/assets/style.css">
</head>
<body>

<header>
    <h1>Система заявок на ремонт</h1>
    <nav>
        <a href="/repairTicketSystem/public/index.php?page=home">Главная</a> |

        <?php if (isset($_SESSION['user'])): ?>
            <?php if ($_SESSION['user']['role'] === 'admin'): ?>
                <a href="/repairTicketSystem/public/index.php?page=admin_panel">Админ-панель</a> |
                <a href="/repairTicketSystem/public/index.php?page=add_slot">Добавить слот</a> |
                <a href="/repairTicketSystem/public/index.php?page=slots">Список слотов</a> |
            <?php else: ?>
                <a href="/repairTicketSystem/public/index.php?page=dashboard">Мои заявки</a> |
            <?php endif; ?>

            <span><strong><?= htmlspecialchars($_SESSION['user']['username']) ?></strong></span> |
            <a href="/repairTicketSystem/public/index.php?page=logout">Выход</a>
        <?php else: ?>
            <a href="/repairTicketSystem/public/index.php?page=login">Вход</a> |
            <a href="/repairTicketSystem/public/index.php?page=register">Регистрация</a>
        <?php endif; ?>
    </nav>
</header>

<main>
    <?php include $templatePath; ?>
</main>

<footer>
    <hr>
    <p>&copy; <?= date('Y') ?> RepairTicketSystem</p>
</footer>

</body>
</html>
