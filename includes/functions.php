<?php
function getPosts()
{
    $posts_file = DATA_DIR . 'posts.json';
    if (file_exists($posts_file)) {
        return json_decode(file_get_contents($posts_file), true) ?: [];
    }
    return [];
}

function savePosts($posts)
{
    file_put_contents(DATA_DIR . 'posts.json', json_encode($posts));
}

function saveImage($image)
{
    $filename = uniqid() . '_' . basename($image['name']);
    $target = UPLOADS_DIR . $filename;

    if (move_uploaded_file($image['tmp_name'], $target)) {
        return $filename;
    }
    return false;
}

function checkAuth()
{
    session_start();
    if (!isset($_SESSION['user_id'])) {
        header('Location: login.php');
        exit;
    }
}

function validateUser($username, $password)
{
    $users = json_decode(file_get_contents(DATA_DIR . 'users.json'), true);
    foreach ($users as $user) {
        if (
            $user['username'] === $username &&
            password_verify($password, $user['password_hash'])
        ) {
            return true;
        }
    }
    return false;
}
