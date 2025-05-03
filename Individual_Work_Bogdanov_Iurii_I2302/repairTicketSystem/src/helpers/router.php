<?php

/**
 * Файл: router.php
 * Назначение: Управляет маршрутизацией страниц веб-приложения repairTicketSystem.
 *
 * Обрабатывает значение параметра `?page=`, подключает соответствующие обработчики
 * и шаблоны, а также ограничивает доступ к защищённым маршрутам (например, админ-панели).
 *
 * @package RepairTicketSystem\Helpers
 */

require_once __DIR__ . '/view.php';

/**
 * Выполняет маршрутизацию на основе переданного имени страницы.
 *
 * Подключает обработчики (`handlers`) и шаблоны (`templates`) в зависимости от запрошенного маршрута.
 * Также обеспечивает защиту маршрутов, требующих авторизации или роли администратора.
 *
 * @param string $page Название страницы, переданное через параметр GET (?page=...).
 *
 * @return void
 */
function route(string $page): void
{
    switch ($page) {
        case 'home':
            render('home');
            break;

        case 'register':
            require_once __DIR__ . '/../handlers/register_handler.php';
            $pdo = getPDO();
            handleRegister($pdo);
            render('register');
            break;

        case 'login':
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                require_once __DIR__ . '/../handlers/login_handler.php';
            }

            // При GET-запросе или после неудачного входа
            render('login', ['loginError' => $_SESSION['loginError'] ?? null]);
            unset($_SESSION['loginError']);
            break;

        case 'logout':
            require_once __DIR__ . '/../handlers/logout_handler.php';
            handleLogout();
            break;

        case 'create_request':
            require_once __DIR__ . '/../handlers/create_request_handler.php';
            handleCreateRequest();
            render('create_request');
            break;

        case 'dashboard':
            if (!isset($_SESSION['user'])) {
                echo '<div class="error-box">Войдите, чтобы просмотреть свои заявки.</div>';
            } else {
                render('dashboard');
            }
            break;

        case 'admin':
            if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
                echo '<div class="error-box">Доступ запрещён</div>';
            } else {
                render('admin');
            }
            break;

        case 'admin_panel':
            if ($_SESSION['user']['role'] === 'admin') {
                render('admin_panel');
            } else {
                echo '<div class="error-box">Доступ запрещён</div>';
            }
            break;

        case 'add_slot':
            if ($_SESSION['user']['role'] === 'admin') {
                require_once __DIR__ . '/../handlers/add_slot_handler.php';
                handleAddSlot();
                render('add_slot');
            } else {
                echo '<div class="error-box">Доступ запрещён</div>';
            }
            break;

        case 'slots':
            if ($_SESSION['user']['role'] === 'admin') {
                render('slots');
            } else {
                echo '<div class="error-box">Доступ запрещён</div>';
            }
            break;

        case 'password_reset_request':
            require_once __DIR__ . '/../handlers/password_reset_request_handler.php';
            handlePasswordResetRequest();
            render('password_reset_request');
            break;

        case 'password_reset':
            require_once __DIR__ . '/../handlers/password_reset_handler.php';
            handlePasswordReset();
            render('password_reset');
            break;

        default:
            render('404');
            break;
    }
}
