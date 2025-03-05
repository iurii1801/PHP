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
    <title>Edit Post</title>
    <link rel="stylesheet" href="/assets/styles/output.css">
</head>
<body>
    <main class="container mx-auto py-6 px-8">
        <h1 class="font-bold text-2xl">Edit Post</h1>
        <form action="/handlers/post-edit-handler.php" method="post" class="mt-4">
            <input type="hidden" name="id" value="<?php echo $id; ?>">
            <label class="block">Title:</label>
            <input type="text" name="title" value="<?php echo htmlspecialchars($post['title']); ?>" required class="border p-2 w-full">
            <label class="block mt-2">Content:</label>
            <textarea name="content" required class="border p-2 w-full h-40"><?php echo htmlspecialchars($post['content']); ?></textarea>
            <div style="display: flex; gap: 10px; margin-top: 20px;">
                <button type="submit" class="bg-blue-700 text-white font-bold py-2 px-4 rounded shadow-md hover:bg-blue-800 transition-all">
                    Save Changes
                </button>
                <button type="button" onclick="window.location.href='/index.php'"
                    class="bg-blue-700 text-white font-bold py-2 px-4 rounded shadow-md hover:bg-blue-800 transition-all">
                    Cancel
                </button>
            </div>
        </form>
    </main>
</body>
</html>