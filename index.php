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
<header>
    <svg width="48" height="48">
        <circle r="18" cx="24" cy="24" fill="transparent" stroke="grey" stroke-width="4"></circle>
        <circle r="6" cx="32" cy="24" fill="transparent" stroke="grey" stroke-width="4"></circle>
    </svg>
    <div id="title">
        <?=t('Taras Labiak') ?>
    </div>
    <?php if (isset($_SESSION['id'])): ?>
        <?=go('profile') ?>
        <?=go('logout') ?>
    <?php else: ?>
        <?=go('signup') ?>
        <?=go('login') ?>
    <?php endif; ?>
</header>
<?php include "$page.php" ?>
<footer>
<?php
$langs = file_get_contents('assets/lang.json');
$langs = json_decode($langs);
var_dump($_SESSION);
foreach($langs as $href => $name)
    if ($lang_href == $href)
        echo "<span>$name</span>";
    else
        echo "<a href='?go=$page&lang=$href'>$name</a>";
?>
</footer>
</body>
