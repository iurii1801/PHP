<?php

/**
 * Файл: password_reset_handler.php
 * Назначение: Обработка сброса пароля пользователя.
 *
 * Этот обработчик проверяет код восстановления, заданный пользователем,
 * и устанавливает новый пароль при успешной проверке.
 *
 * @package RepairTicketSystem\Handlers
 */

require_once __DIR__ . '/../helpers/db.php';

/**
 * Обрабатывает форму сброса пароля.
 *
 * Проверяет email, код сброса и новый пароль.
 * Если данные корректны — обновляет пароль в базе данных.
 *
 * @return void
 */
function handlePasswordReset(): void
{
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        return;
    }

    $email = trim($_POST['email'] ?? '');
    $code = trim($_POST['reset_code'] ?? '');
    $newPassword = $_POST['new_password'] ?? '';

    if (!$email || !$code || !$newPassword) {
        echo '<div class="error-box">Заполните все поля</div>';
        return;
    }

    if (strlen($newPassword) < 6) {
        echo '<div class="error-box">Пароль должен содержать не менее 6 символов</div>';
        return;
    }

    $pdo = getPDO();

    // Проверка правильности email и кода восстановления
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ? AND reset_code = ?");
    $stmt->execute([$email, $code]);

    if ($stmt->rowCount() === 0) {
        echo '<div class="error-box">Неверный email или код восстановления</div>';
        return;
    }

    // Хеширование и обновление пароля
    $hashed = password_hash($newPassword, PASSWORD_DEFAULT);
    $pdo->prepare("UPDATE users SET password = ?, reset_code = NULL WHERE email = ?")->execute([$hashed, $email]);

    echo '<div class="success-box">Пароль успешно сброшен! <a href="/repairTicketSystem/public/index.php?page=login">Войти</a></div>';
}
