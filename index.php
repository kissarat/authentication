<?php  require_once 'init.php'; ?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8" />
    <title>Taras Labiak</title>
    <script src="assets/script.js"></script>
    <link rel="stylesheet" type="text/css" href="assets/style.css" />
</head>
<body>
<nav>
    <svg width="48" height="48">
        <circle r="18" cx="24" cy="24" fill="transparent" stroke="grey" stroke-width="4"></circle>
        <circle r="6" cx="32" cy="24" fill="transparent" stroke="grey" stroke-width="4"></circle>
    </svg>
    <div id="title">
        <?=t('Taras Labiak') ?>
    </div>
    <?php if ($user): ?>
        <?=anchor('profile') ?>
        <?=anchor('logout') ?>
    <?php else: ?>
        <?=anchor('signup') ?>
        <?=anchor('login') ?>
    <?php endif; ?>
</nav>
<?php include "$page.php" ?>
</body>
