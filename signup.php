<?php
require_once "init.php";

function form($error = '') {
    ?>
    <form action="?go=signup" method="post">
        <div><?=$error ?></div>
        <?=email() ?>
        <?=password() ?>
        <?=password('repeat') ?>
        <input type="hidden" name="MAX_FILE_SIZE" value="65536" />
        <input id="avatar" name="avatar" type="file"/>
        <?=submit('signup') ?>
    </form>
    <?php
}

if ('GET' == $_SERVER['REQUEST_METHOD'])
    form();
elseif ('POST' == $_SERVER['REQUEST_METHOD']) {
    $error = '';
    foreach(['email', 'password', 'repeat'] as $name)
        if (empty($_POST[$name])) {
            form('required', [$name]);
            return;
        }
    if ($_POST['password'] != $_POST['repeat'])
        form('password mismatch', ['password', 'repeat']);
    elseif (strlen($_POST['password']) < 4)
        form('too short', ['password', 'repeat']);
    elseif (!preg_match('/[0-9A-Za-z!@#\$%]+/', $_POST['password']))
        form('invalid password format', ['password']);
    else {
        $password = hash('sha512', $_POST['password'], true);
        $stat = $db->prepare('INSERT INTO `user`(`email`, `password`, `salt`) VALUES (?, ?, ?)');
        $stat->execute([$_POST['email'], $password, session_id()]);
        $id = $db->lastInsertId();
        $stat = $db->prepare('SELECT `id`, `email`, `salt` FROM `user` WHERE `id`=?');
        $stat->execute([$id]);
        $_SESSION = array_merge($_SESSION, $stat->fetch(PDO::FETCH_ASSOC));
    }
}
else
    form("http method ${_SERVER['REQUEST_METHOD']} does not supported");