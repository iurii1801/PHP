<?php

/**
 * Файл: login.php
 * Назначение: Страница авторизации пользователя в системе RepairTicketSystem.
 * Отображает форму входа и выводит сообщение об ошибке, если вход не удался.
 *
 * @package RepairTicketSystem\Templates
 * @var string $templatePath Путь к текущему шаблону (для layout.php)
 * @var string|null $loginError Сообщение об ошибке входа, если присутствует
 */

$templatePath = __FILE__;
?>

<h2>Вход</h2>

<?php if (!empty($loginError)): ?>
    <div class="error-box"><?= htmlspecialchars($loginError) ?></div>
<?php endif; ?>

<form action="/repairTicketSystem/public/index.php?page=login" method="POST" class="main-form">
    <label for="username">Имя пользователя или Email</label>
    <input type="text" name="username" id="username" required>

    <label for="password">Пароль</label>
    <input type="password" name="password" id="password" required>

    <button type="submit" class="button">Войти</button>

    <p>
        <a href="/repairTicketSystem/public/index.php?page=password_reset_request">
            Забыли пароль?
        </a>
    </p>
</form>
