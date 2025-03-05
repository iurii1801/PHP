<?php
require_once './data/posts.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] - 1 : 0;
if (!isset($posts[$id])) {
    die('Post not found');
}
$post = $posts[$id];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($post['title']); ?></title>
    <link rel="stylesheet" href="/assets/styles/output.css">
</head>
<body>
    <main class="container mx-auto py-6 px-8">
        <div class="flex justify-between items-center">
            <h1 class="font-bold text-3xl"><?php echo htmlspecialchars($post['title']); ?></h1>
            <a href="/article-edit.php?id=<?php echo $id + 1; ?>">
                <button class="bg-blue-700 rounded-md px-3 py-2 text-white cursor-pointer">Edit</button>
            </a>
        </div>
        <p class="text-gray-600 mt-4"><?php echo nl2br(htmlspecialchars($post['content'])); ?></p>
        <i class="text-gray-500"><?php echo $post['date']; ?></i>
    </main>
</body>
</html>
