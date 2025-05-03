<?php

/**
 * Файл: password_reset_request_handler.php
 * Назначение: Обработка запроса на сброс пароля пользователя.
 *
 * Этот скрипт генерирует код восстановления и сохраняет его в БД.
 * В реальной системе код должен отправляться пользователю по email.
 *
 * @package RepairTicketSystem\Handlers
 */

require_once __DIR__ . '/../helpers/db.php';

/**
 * Обрабатывает запрос на восстановление пароля.
 *
 * Принимает email из формы, проверяет наличие пользователя с таким email,
 * и генерирует 6-значный числовой код, который сохраняется в таблице users.
 * В реальной реализации код должен быть отправлен пользователю на email.
 *
 * @return void
 */
function handlePasswordResetRequest(): void
{
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        return;
    }

    $email = trim($_POST['email'] ?? '');

    if (empty($email)) {
        echo '<div class="error-box">Пожалуйста, введите email</div>';
        return;
    }

    $pdo = getPDO();
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$email]);

    if ($stmt->rowCount() === 0) {
        echo '<div class="error-box">Email не найден</div>';
        return;
    }

    $code = strval(rand(100000, 999999)); // Генерация 6-значного кода

    $pdo->prepare("UPDATE users SET reset_code = ? WHERE email = ?")->execute([$code, $email]);

    // Вывод сообщения (в реальности код должен отправляться на email)
    echo "<div class='success-box'>Код сброса: <strong>$code</strong> (в реальной системе он был бы отправлен по email)</div>";
    echo "<p><a href='/repairTicketSystem/public/index.php?page=password_reset'>Перейти к восстановлению</a></p>";
}
