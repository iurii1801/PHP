<?php

/**
 * Файл: create_request.php
 * Назначение: Отображает форму создания заявки на ремонт.
 * 
 * Доступен только авторизованным пользователям.
 * Позволяет выбрать категорию услуги, тип устройства, указать описание проблемы,
 * уровень срочности и выбрать свободный временной слот.
 *
 * @package RepairTicketSystem\Templates
 */

$templatePath = __FILE__;

require_once __DIR__ . '/../models/CategoryModel.php';
require_once __DIR__ . '/../models/SlotModel.php';
require_once __DIR__ . '/../models/DeviceModel.php';

// Получение всех данных для заполнения формы
$devices = DeviceModel::getAll();
$slots = SlotModel::getAvailable();
$categories = CategoryModel::getAll();

// Проверка авторизации пользователя
if (!isset($_SESSION['user'])) {
    echo '<div class="error-box">Вы должны <a href="/repairTicketSystem/public/index.php?page=login">войти</a>, чтобы оставить заявку.</div>';
    return;
}

$catId = (int) ($_GET['cat_id'] ?? 0);
?>

<h2>Создание заявки</h2>

<form method="POST" action="/repairTicketSystem/public/index.php?page=create_request" class="main-form">

    <label for="category_id">Категория</label>
    <select name="category_id" required>
        <?php foreach ($categories as $cat): ?>
            <option value="<?= $cat['id'] ?>" <?= $cat['id'] === $catId ? 'selected' : '' ?>>
                <?= htmlspecialchars($cat['name']) ?>
            </option>
        <?php endforeach; ?>
    </select>

    <label for="device_id">Тип устройства</label>
    <select name="device_id" required>
        <?php foreach ($devices as $device): ?>
            <option value="<?= $device['id'] ?>">
                <?= htmlspecialchars($device['name']) ?>
            </option>
        <?php endforeach; ?>
    </select>

    <label for="problem_description">Описание проблемы</label>
    <textarea name="problem_description" required></textarea>

    <label for="urgency">Срочность</label>
    <select name="urgency" required>
        <option value="">Выберите уровень</option>
        <option value="низкая">Низкая</option>
        <option value="средняя">Средняя</option>
        <option value="высокая">Высокая</option>
    </select>

    <label for="time_slot_id">Выберите слот</label>
    <select name="time_slot_id" required>
        <option value="">Выберите время</option>
        <?php foreach ($slots as $slot): ?>
            <option value="<?= $slot['id'] ?>">
                <?= date('d.m.Y H:i', strtotime($slot['slot_time'])) ?>
            </option>
        <?php endforeach; ?>
    </select>

    <button type="submit" class="button">Отправить заявку</button>
</form>
