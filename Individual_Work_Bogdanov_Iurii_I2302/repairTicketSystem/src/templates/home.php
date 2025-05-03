<?php

/**
 * Файл: home.php
 * Назначение: Отображение доступных категорий ремонта на главной странице.
 *
 * Шаблон показывает список категорий, полученных из базы данных.
 * Каждая категория содержит название, описание и кнопку записи.
 *
 * @package RepairTicketSystem\Templates
 * @var string $templatePath Путь к текущему шаблону.
 */

$templatePath = __FILE__;
require_once __DIR__ . '/../models/CategoryModel.php';

// Получаем список категорий из модели
$categories = CategoryModel::getAll();
?>

<h2>Доступные категории ремонта</h2>

<!-- Список категорий -->
<ul class="recipe-list">
    <?php foreach ($categories as $cat): ?>
        <li>
            <strong><?= htmlspecialchars($cat['name']) ?></strong><br>
            <?= nl2br(htmlspecialchars($cat['description'])) ?>
            <div class="actions" style="margin-top: 10px;">
                <!-- Ссылка на создание заявки с предустановленной категорией -->
                <a class="details-button" href="/repairTicketSystem/public/index.php?page=create_request&cat_id=<?= $cat['id'] ?>">
                    Записаться
                </a>
            </div>
        </li>
    <?php endforeach; ?>
</ul>
