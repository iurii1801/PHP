<?php
require_once __DIR__ . '/../data/posts.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = isset($_POST['id']) ? (int)$_POST['id'] : -1;
    $title = isset($_POST['title']) ? trim($_POST['title']) : '';
    $content = isset($_POST['content']) ? trim($_POST['content']) : '';
    
    if ($id < 0 || empty($title) || empty($content)) {
        die('Invalid input data');
    }
    
    // Проверяем, существует ли пост
    if (!isset($posts[$id])) {
        die('Post not found');
    }
    
    // Обновляем данные поста
    $posts[$id]['title'] = $title;
    $posts[$id]['content'] = $content;
    
    // Сохраняем изменения
    file_put_contents(__DIR__ . '/../data/posts.json', json_encode($posts, JSON_PRETTY_PRINT));
    
    echo "<div style='display: flex; flex-direction: column; align-items: center; justify-content: center; height: 100vh; text-align: center;'>";
    echo "<p style='color: green; font-weight: bold; font-size: 24px;'>Changes saved successfully!</p>";
    echo "<a href='/index.php' style='text-decoration: none;'>";
    echo "<button style='background-color: blue; color: white; padding: 10px 20px; border: none; border-radius: 5px; font-size: 18px; cursor: pointer;'>Go Back</button>";
    echo "</a></div>";
    exit;
} else {
    die('Invalid request');
}