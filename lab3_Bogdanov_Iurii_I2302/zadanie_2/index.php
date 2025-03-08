<?php
declare(strict_types=1);

/**
 * Получает список изображений формата .jpg из указанной директории.
 *
 * @param string $dir Путь к папке с изображениями
 * @return array Массив имен файлов изображений
 */
function getImages(string $dir): array {
    $files = scandir($dir);
    if ($files === false) {
        return [];
    }
    return array_filter($files, fn($file) => is_file($dir . $file) && pathinfo($file, PATHINFO_EXTENSION) === 'jpg');
}

$dir = 'image/';
$images = getImages($dir);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Car Gallery</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

<div class="header">
    <nav class="menu">
        <a href="#">Supercars</a> | 
        <a href="#">News</a> | 
        <a href="#">Contacts</a>
    </nav>
</div>

<h2>#cars</h2>
<p>Explore the world of supercars</p>

<div class="gallery">
    <?php foreach ($images as $image): ?>
        <img src="<?php echo $dir . $image; ?>" alt="Car Image">
    <?php endforeach; ?>
</div>

<div class="footer">
    <p>Supercars Gallery © 2024</p>
</div>

</body>
</html>
