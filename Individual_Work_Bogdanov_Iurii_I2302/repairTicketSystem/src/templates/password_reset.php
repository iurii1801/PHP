<?php

/**
 * Файл: password_reset.php
 * Назначение: Шаблон для ввода кода восстановления, email и нового пароля.
 * Используется для завершения процедуры сброса пароля пользователя.
 *
 * @package RepairTicketSystem\Templates
 * @var string $templatePath Путь к текущему шаблону (для layout.php)
 */

$templatePath = __FILE__;
?>

<h2>Сброс пароля</h2>

<form method="POST" action="/repairTicketSystem/public/index.php?page=password_reset" class="main-form">
    <label for="reset_code">Код восстановления</label>
    <input type="text" name="reset_code" id="reset_code" required>

    <label for="email">Email</label>
    <input type="email" name="email" id="email" required>

    <label for="new_password">Новый пароль</label>
    <input type="password" name="new_password" id="new_password" required minlength="6" placeholder="Не менее 6 символов">

    <button type="submit" class="button">Установить новый пароль</button>
</form>
