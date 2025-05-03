<?php

/**
 * Файл: register.php
 * Назначение: Шаблон страницы регистрации нового пользователя в системе.
 * Предоставляет форму с обязательными полями: имя пользователя, email, пароль и подтверждение пароля.
 *
 * @package RepairTicketSystem\Templates
 * @var string $templatePath Путь к текущему шаблону (используется в layout.php)
 */

$templatePath = __FILE__;
?>

<h2>Регистрация</h2>

<form action="/repairTicketSystem/public/index.php?page=register" method="POST" class="main-form">
    <label for="username">Имя пользователя</label>
    <input type="text" name="username" id="username" required>

    <label for="email">Email</label>
    <input type="email" name="email" id="email" required>

    <label for="password">Пароль</label>
    <input type="password" name="password" id="password" required>

    <label for="confirm_password">Подтвердите пароль</label>
    <input type="password" name="confirm_password" id="confirm_password" required>

    <button type="submit" class="button">Зарегистрироваться</button>
</form>
