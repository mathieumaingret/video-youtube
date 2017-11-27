<?php require_once '../src/youtube.class.php'; ?>
<?php $video = new VideoYoutube('https://www.youtube.com/watch?v=2106qUYzqJg&list=RD2106qUYzqJg&t=3'); ?>

<!doctype html>
    <html lang="en">
    <head>
        <meta charset="utf-8">
        <title>Youtube examples</title>

        <style>
            body {
                font-family: Helvetica;
                text-align: center;
            }
        </style>
    </head>
    <body>

        <?php if ($video->isValid()): ?>

            <article>
                <h2>Thumbnail :</h2>
                <?php echo $video->render('thumbnail', array(
                    'width' => 200,
                    'title' => 'My custom title attribute'
                )); ?>
            </article>

            <article>
                <h2>Iframe</h2>
                <?php echo $video->render('iframe', array()); ?>
            </article>

            <article>
                <h2>Author</h2>
                <?php if ($author = $video->get('author')): ?>
                    <a href="<?php echo $author['channel'] ?>" target="_blank">Visit <?php echo $author['name']; ?>'s channel</a>
                <?php endif; ?>
            </article>

        <?php else: ?>
            <p>Invalid.</p>
        <?php endif; ?>

    </body>
</html>