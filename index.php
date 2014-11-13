<?php  require_once 'init.php'; ?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8" />
    <link rel="stylesheet" type="text/css" href="style.css" />
</head>
<body>
<nav>
    <?php if ($user): ?>
        <h1><?=t('Authentication') ?></h1>
        <?=anchor('profile') ?>
        <?=anchor('logout') ?>
    <?php else: ?>
        <?=anchor('login') ?>
        <?=anchor('signup') ?>
    <?php endif; ?>
</nav>
<?php include "$page.php" ?>
</body>
