<?php
require_once "init.php";

function form($error = '') {
    ?>
    <form action="?go=signup" method="post">
        <div><?=$error ?></div>
        <?=email() ?>
        <?=password() ?>
        <?=password('repeat') ?>
        <?=submit('signup') ?>
    </form>
    <?php
    exit(0);
}

if ('GET' == $_SERVER['REQUEST_METHOD'])
    form();
elseif ('POST' == $_SERVER['REQUEST_METHOD']) {
    $error = '';
    if (array_key_exists(['email', 'password', 'repeat'], $_POST)) {
        if ($_POST['password'] != $_POST['repeat'])
            form('password mismatch', ['password', 'repeat']);
    }
    form('required');
}
else
    form('http method   does not supported');