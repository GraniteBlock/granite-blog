<?php
session_start();
require_once 'includes/config.php';
require_once 'includes/functions.php';

$posts = getPosts();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Granite Kingdom</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600&family=Open+Sans:wght@400;600&display=swap" rel="stylesheet">
    <meta property="og:description" content="Made with love by BRP0415 and Granite">
    <meta property="og:image" content="https://static.wikia.nocookie.net/minecraft_gamepedia/images/7/7e/Granite_JE2_BE2.png/revision/latest?cb=20200315183719">
    <link rel="icon" href="https://static.wikia.nocookie.net/minecraft_gamepedia/images/7/7e/Granite_JE2_BE2.png/revision/latest?cb=20200315183719" type="image/png">
    <style>
        :root {
            --cream: #fdf6ee;
            --accent: #d4a373;
            --text: #4e342e;
            --shadow: rgba(0, 0, 0, 0.1);
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Open Sans', sans-serif;
            background: var(--cream);
            color: var(--text);
            line-height: 1.6;
            transition: all 0.3s ease-in-out;
        }

        header {
            background: var(--cream);
            color: var(--text);
            padding: 3rem 1rem 2rem 1rem;
            text-align: center;
            border-bottom: 2px solid var(--accent);
        }

        header h1 {
            font-family: 'Playfair Display', serif;
            font-size: 3.5rem;
            letter-spacing: 2px;
            font-weight: 600;
        }

        header p {
            margin-top: 1rem;
            font-size: 1.2rem;
            color: var(--accent);
        }

        .container {
            max-width: 1300px;
            margin: 4rem auto;
            padding: 0 2rem;
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
            gap: 2.5rem;
        }

        .post {
            background: #fff8ef;
            border-radius: 16px;
            padding: 2rem;
            box-shadow: 0 10px 30px var(--shadow);
            transition: transform 0.4s ease, box-shadow 0.4s ease;
            border: 1px solid #f0e1ce;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            position: relative;
            overflow: hidden;
            cursor: pointer;
            height: 130px;
        }

        .post::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 6px;
            background: var(--accent);
            border-radius: 16px 16px 0 0;
        }

        .post:hover {
            transform: scale(1.03);
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.15);
        }

        .post h2 {
            font-family: 'Playfair Display', serif;
            margin-bottom: 0.75rem;
            color: var(--text);
            font-size: 1.6rem;
        }

        .post .meta {
            font-size: 0.85rem;
            color: #a08673;
            margin-bottom: 1rem;
        }

        .post p {
            font-size: 1rem;
            color: #5e473a;
        }

        .secret-btn {
            display: block;
            margin: 3rem auto;
            padding: 1rem 2rem;
            background: var(--accent);
            color: white;
            font-size: 1.1rem;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        .secret-btn:hover {
            background: #c78b58;
        }

        footer {
            text-align: center;
            padding: 3rem 1rem;
            background: #f8efe2;
            color: #7d5a4d;
            margin-top: 4rem;
            border-top: 2px solid var(--accent);
            font-size: 0.95rem;
        }

        @media screen and (max-width: 768px) {
            header h1 {
                font-size: 2.5rem;
            }
        }

        .post .image-gallery {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 1rem;
        }

        .post .image-gallery img {
            max-width: 100px;
            max-height: 100px;
            object-fit: cover;
            border-radius: 8px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            cursor: pointer;
        }

        .post-content {
            display: none;
            margin-top: 1rem;
        }

        .post.expanded {
            height: auto;
        }

        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.7);
            align-items: center;
            justify-content: center;
            z-index: 9999;
        }

        .modal img {
            max-width: 90%;
            max-height: 90%;
            margin: auto;
            border-radius: 10px;
        }

        .modal.active {
            display: flex;
        }
    </style>
</head>

<body>
    <header>
        <h1>Granite Blog</h1>
        <p>Solid Thoughts, Timeless Reads</p>
    </header>

    <div class="container" id="blog-posts">
        <?php foreach ($posts as $post): ?>
            <div class="post">
                <h2><?= htmlspecialchars($post['title']) ?></h2>
                <div class="meta"><?= htmlspecialchars($post['date']) ?></div>
                <div class="post-content">
                    <p><?= htmlspecialchars($post['content']) ?></p>
                    <div class="image-gallery">
                        <?php foreach ($post['images'] as $image): ?>
                            <img src="uploads/<?= htmlspecialchars($image) ?>"
                                alt="Post Image"
                                class="post-image"
                                data-image="uploads/<?= htmlspecialchars($image) ?>">
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <button class="secret-btn" onclick="goToDev()">Dev Portal</button>

    <footer>
        <p>Made with love by BRP0415 and art3m4ik3 and Granite</p>
    </footer>

    <div class="modal" id="imageModal">
        <img src="" id="expandedImage" alt="Expanded Image">
    </div>

    <script>
        document.querySelectorAll('.post').forEach(post => {
            post.addEventListener('click', function() {
                this.classList.toggle('expanded');
                this.querySelector('.post-content').style.display =
                    this.classList.contains('expanded') ? 'block' : 'none';
            });
        });

        const modal = document.getElementById('imageModal');
        const expandedImage = document.getElementById('expandedImage');

        document.querySelectorAll('.post-image').forEach(image => {
            image.addEventListener('click', function(e) {
                e.stopPropagation();
                expandedImage.src = this.getAttribute('data-image');
                modal.classList.add('active');
            });
        });

        modal.addEventListener('click', function() {
            modal.classList.remove('active');
        });

        function goToDev() {
            window.location.href = "admin/login.php";
        }
    </script>
</body>

</html>
