<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';
checkAuth();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['index'])) {
    $posts = getPosts();
    $index = (int)$_POST['index'];

    if (isset($posts[$index])) {
        foreach ($posts[$index]['images'] as $image) {
            $image_path = UPLOADS_DIR . $image;
            if (file_exists($image_path)) {
                unlink($image_path);
            }
        }

        array_splice($posts, $index, 1);
        savePosts($posts);
    }
}

header('Location: admin.php');
exit;
