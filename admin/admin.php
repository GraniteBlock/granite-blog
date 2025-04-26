<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';
checkAuth();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'] ?? '';
    $content = $_POST['content'] ?? '';

    $images = [];
    if (!empty($_FILES['images'])) {
        foreach ($_FILES['images']['tmp_name'] as $key => $tmp_name) {
            if ($_FILES['images']['error'][$key] === UPLOAD_ERR_OK) {
                $imageFile = [
                    'name' => $_FILES['images']['name'][$key],
                    'tmp_name' => $tmp_name,
                    'error' => $_FILES['images']['error'][$key]
                ];
                if ($filename = saveImage($imageFile)) {
                    $images[] = $filename;
                }
            }
        }
    }

    $post = [
        'title' => $title,
        'content' => $content,
        'date' => date('F j, Y'),
        'images' => $images
    ];

    $posts = getPosts();
    array_unshift($posts, $post);
    savePosts($posts);

    header('Location: admin.php');
    exit;
}

$posts = getPosts();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Granite Blog - Admin Portal</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600&family=Open+Sans:wght@400;600&display=swap" rel="stylesheet">
    <meta property="og:image" content="https://static.wikia.nocookie.net/minecraft_gamepedia/images/7/7e/Granite_JE2_BE2.png/revision/latest?cb=20200315183719">
    <link rel="icon" href="https://static.wikia.nocookie.net/minecraft_gamepedia/images/7/7e/Granite_JE2_BE2.png/revision/latest?cb=20200315183719" type="image/png">
    <style>
        body {
            background-color: #fdf6ee;
            font-family: 'Open Sans', sans-serif;
            color: #4e342e;
            padding: 2rem;
        }

        h1 {
            text-align: center;
            font-size: 2.5rem;
            font-family: 'Playfair Display', serif;
            color: #d4a373;
        }

        form {
            max-width: 700px;
            margin: 2rem auto;
            background: #fff8ef;
            padding: 2rem;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        label {
            display: block;
            margin-top: 1rem;
            font-weight: bold;
        }

        input[type="text"],
        textarea {
            width: 100%;
            padding: 0.75rem;
            margin-top: 0.5rem;
            border: 1px solid #e3cbb4;
            border-radius: 8px;
            background-color: #fffdf8;
            font-size: 1rem;
        }

        input[type="file"] {
            display: none;
        }

        .custom-file-label {
            display: inline-block;
            margin-top: 0.75rem;
            padding: 0.75rem 1.5rem;
            background-color: #d4a373;
            color: white;
            border-radius: 10px;
            cursor: pointer;
            font-size: 1rem;
            text-align: center;
            transition: background 0.3s ease;
        }

        .custom-file-label:hover {
            background-color: #c78b58;
        }

        #file-name-display {
            margin-top: 0.5rem;
            font-size: 0.9rem;
            color: #7d5a4d;
        }

        #image-preview {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 1rem;
        }

        #image-preview img {
            max-width: 100px;
            max-height: 100px;
            object-fit: cover;
            border-radius: 8px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        button {
            margin-top: 2rem;
            padding: 0.75rem 2rem;
            background-color: #d4a373;
            color: white;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            font-size: 1.1rem;
            display: block;
            margin-left: auto;
            margin-right: auto;
        }

        button:hover {
            background-color: #c78b58;
        }

        .post-list {
            margin-top: 2rem;
            background-color: #fff8ef;
            padding: 1rem;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        .post-item {
            display: flex;
            justify-content: space-between;
            padding: 1rem;
            border-bottom: 1px solid #e3cbb4;
        }

        .delete-btn {
            background-color: #e57373;
            color: white;
            padding: 0.5rem 1rem;
            border: none;
            border-radius: 8px;
            cursor: pointer;
        }

        .delete-btn:hover {
            background-color: #d32f2f;
        }
    </style>
</head>

<body>
    <h1>Granite Blog - Admin Portal</h1>

    <form id="postForm" method="POST" enctype="multipart/form-data">
        <label for="title">Post Title</label>
        <input type="text" name="title" id="title" required>

        <label for="content">Content</label>
        <textarea name="content" id="content" rows="6" required></textarea>

        <label for="images">Add Images (up to 5)</label>
        <label for="images" class="custom-file-label">Choose Images</label>
        <input type="file" name="images[]" id="images" accept="image/*" multiple>
        <div id="file-name-display"></div>
        <div id="image-preview"></div>

        <button type="submit">Publish</button>
    </form>

    <div class="post-list">
        <h2>Existing Posts</h2>
        <?php foreach ($posts as $index => $post): ?>
            <div class="post-item">
                <div>
                    <h3><?= htmlspecialchars($post['title']) ?></h3>
                    <p><?= htmlspecialchars($post['date']) ?></p>
                </div>
                <form method="POST" action="delete.php" style="margin: 0;">
                    <input type="hidden" name="index" value="<?= $index ?>">
                    <button type="submit" class="delete-btn">Delete</button>
                </form>
            </div>
        <?php endforeach; ?>
    </div>

    <script>
        const imageInput = document.getElementById('images');
        const fileNameDisplay = document.getElementById('file-name-display');
        const previewContainer = document.getElementById('image-preview');

        imageInput.addEventListener('change', function() {
            const files = Array.from(this.files);
            fileNameDisplay.textContent = files.map(f => f.name).join(', ') || 'No files chosen';

            previewContainer.innerHTML = '';
            files.forEach(file => {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    previewContainer.appendChild(img);
                };
                reader.readAsDataURL(file);
            });
        });
    </script>
</body>

</html>
