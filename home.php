<?php  require_once 'init.php'; ?>
<article>
    <?=t('welcome') .
    ($user ? ', ' . $user['email'] : '!<br/>' . t('please sign in or sign up')); ?>
</article>