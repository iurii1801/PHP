<?php

/**
 * Файл: index.php
 * Назначение: Главная точка входа для веб-приложения repairTicketSystem.
 *
 * Этот файл:
 * - запускает сессию;
 * - подключает маршрутизатор и систему отображения шаблонов;
 * - обрабатывает входящие HTTP-запросы на основе параметра `page`;
 * - инициирует рендеринг соответствующей страницы через функцию route().
 *
 * @package RepairTicketSystem\Public
 */

// Запускаем сессию для отслеживания состояния пользователя
session_start();

// Подключаем вспомогательные модули: маршрутизацию и отображение
require_once __DIR__ . '/../src/helpers/router.php';
require_once __DIR__ . '/../src/helpers/view.php';

// Получаем значение параметра "page" из URL, по умолчанию — 'home'
$page = $_GET['page'] ?? 'home';

// Передаём управление в функцию маршрутизации
route($page);
