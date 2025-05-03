<?php

/**
 * Файл: login_handler.php
 * Назначение: Обрабатывает вход пользователя в систему.
 *
 * Проверяет введённые учетные данные (имя пользователя и пароль),
 * сравнивает с сохранёнными в базе данных, создаёт сессию при успешном входе
 * или возвращает ошибку при неудаче.
 *
 * @package RepairTicketSystem\Handlers
 */

require_once __DIR__ . '/../helpers/db.php';

$username = trim($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';

$pdo = getPDO();

// Поиск пользователя по имени
$stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
$stmt->execute([$username]);
$user = $stmt->fetch();

// Проверка пароля и вход
if ($user && password_verify($password, $user['password'])) {
    // Сохраняем данные пользователя в сессию
    $_SESSION['user'] = $user;

    // Перенаправление на главную страницу после успешного входа
    header('Location: /repairTicketSystem/public/index.php?page=home');
    exit;
} else {
    // Установка ошибки для отображения на форме
    $_SESSION['loginError'] = 'Неверное имя пользователя или пароль.';
    
    // Перенаправление обратно на страницу входа
    header('Location: /repairTicketSystem/public/index.php?page=login');
    exit;
}
