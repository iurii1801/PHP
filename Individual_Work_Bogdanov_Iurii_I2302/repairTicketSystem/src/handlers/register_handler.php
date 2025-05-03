<?php

/**
 * Файл: register_handler.php
 * Назначение: Обработка формы регистрации с валидацией данных пользователя.
 *
 * Проверяет правильность ввода имени пользователя, email и пароля,
 * предотвращает регистрацию дубликатов, хеширует пароль и сохраняет
 * данные нового пользователя в базу данных.
 *
 * @package RepairTicketSystem\Handlers
 */

require_once __DIR__ . '/../helpers/db.php';

/**
 * Обрабатывает регистрацию пользователя при POST-запросе.
 *
 * Выполняется:
 * - проверка на пустые поля;
 * - валидация email;
 * - сравнение паролей;
 * - проверка уникальности имени/email;
 * - хеширование пароля;
 * - сохранение пользователя в БД;
 * - вывод ошибок или подтверждения.
 *
 * @param PDO $pdo Подключение к базе данных через PDO.
 *
 * @return void
 */
function handleRegister(PDO $pdo): void
{
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') return;

    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';

    $errors = [];

    // Валидация имени
    if (empty($username)) {
        $errors[] = 'Имя пользователя обязательно';
    }

    // Валидация email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Некорректный email';
    }

    // Проверка длины пароля
    if (strlen($password) < 6) {
        $errors[] = 'Пароль должен быть не менее 6 символов';
    }

    // Сравнение паролей
    if ($password !== $confirmPassword) {
        $errors[] = 'Пароли не совпадают';
    }

    // Проверка уникальности пользователя
    $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
    $stmt->execute([$username, $email]);
    if ($stmt->rowCount() > 0) {
        $errors[] = 'Пользователь с таким именем или email уже существует';
    }

    // Если есть ошибки — вывести список
    if ($errors) {
        echo '<div class="error-box"><ul>';
        foreach ($errors as $error) {
            echo "<li>$error</li>";
        }
        echo '</ul></div>';
        return;
    }

    // Хеширование пароля и сохранение
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
    $stmt->execute([$username, $email, $hashedPassword]);

    // Успешная регистрация
    echo '<div class="success-box">Регистрация прошла успешно. <a href="/repairTicketSystem/public/index.php?page=login">Войти</a></div>';
}
