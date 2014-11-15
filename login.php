<?php
require_once "init.php";

function form($error = '')
{
    ?>
    <form action="?go=login" method="post">
        <div><?=$error ?></div>
        <?= email() ?>
        <?= password() ?>
        <?= submit('login') ?>
    </form>
<?php
}

function auth_log($status) {
    global $db;
    $stat = $db->prepare('INSERT INTO `auth` VALUES (?, ?, ?)');
    $stat->execute([$_POST['email'], $_SERVER['HTTP_USER_AGENT'], $status]);
}

if (isset($_SESSION['id'])) {
?>
    <article>
        <?=t('you are already login') ?><br/>
        <?=go('logout') ?>
    </article>
<?php
}
elseif ('GET' == $_SERVER['REQUEST_METHOD'])
    form();
elseif ('POST' == $_SERVER['REQUEST_METHOD']) {
    $error = '';
    if (empty($_POST['email']))
        form('required', ['email']);
    elseif (empty($_POST['password']))
        form('required', ['password']);
    else {
        $stat = $db->prepare('SELECT `id`, `salt`, `password` FROM `user` WHERE `email`=?');
        $stat->execute([$_POST['email']]);
        if (0 == $stat->rowCount()) {
            form('invalid user or password');
            return;
        }
        $user = $stat->fetch(PDO::FETCH_ASSOC);
        if (hash('sha512', $_POST['password'], true) != $user['password']) {
            auth_log(false);
            form('invalid user or password');
        }
        else {
            unset($_POST);
            session_destroy();
            session_id($user['salt']);
            session_start();
            $_SESSION['id'] = $user['id'];
            $_SESSION['email'] = $_POST['email'];
            auth_log(true);
        }
        $db = null;
    }
}
else
    form("http method ${_SERVER['REQUEST_METHOD']} does not supported");
