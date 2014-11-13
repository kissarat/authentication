<?php
require_once "init.php";
?>
<form action="?go=signup" method="post">
    <?=email() ?>
    <?=password() ?>
    <?=submit('login') ?>
</form>