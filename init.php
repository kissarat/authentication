<?php
require 'session.php';


function connect()
{
    $host = getenv('IP');
    if (!$host)
        $host = 'localhost';
    $user = getenv('C9_USER');
    if (!$user)
        $user = 'kissarat';
    return new PDO("mysql:host=$host;dbname=c9", $user);
}

if (isset($_GET['lang'])) {
    $lang_href = $_GET['lang'];
    $_SESSION['lang'] = $lang_href;
}
elseif (isset($_SESSION['lang']))
    $lang_href = $_SESSION['lang'];
else
    $lang_href = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);

$lang = file_get_contents("lang/$lang_href.json");
$lang = json_decode($lang, true);

function t($message) {
    global $lang;
    return isset($lang[$message]) ? $lang[$message] : $message;
}

$db = connect();
$user = null;

if (!isset($_SESSION['id'])) {
    $stat = $db->prepare('SELECT `id` FROM `user` WHERE `salt`=?');
    $stat->execute([session_id()]);
    if (1 == $stat->rowCount()) {
        $user = $stat->fetch(PDO::FETCH_ASSOC);
    }
}

$page = isset($_GET['go']) ? $_GET['go'] : 'home';

if ('logout' == $page) {
    session_destroy();
    $go = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '?go=home';
    header('Location: ' . $go, false, 302);
    exit();
}

if (!in_array($page, ['home', 'login', 'signup']))
    $page = 'error';

function go($go) {
    global $page;
    $text = t($go);
    if ($page == $go)
        return "<span>$text</span>";
    else
        return "<a href=\"?go=$go\">$text</a>";
}

function input($id, $type, $required, $other='') {
    if ($required)
        $required = 'required="required"';
    $label = t($id);
    $value = isset($_POST[$id]) ? $_POST[$id] : '';
    return
        "<div>
            <label for='$id'>$label</label>
            <input name='$id' value='$value' id='$id' type='$type' $required $other />
        </div>";
}

function email() {
    return input('email', 'email', true, "pattern='^[^@]+@[^@]+\\.[^@]{2,}$'");
}

function password($id='password') {
    return input($id, 'password', true);
}

function submit($text) {
    $text = t($text);
    return
        "<div id='buttons'>
            <span></span>
            <button type='submit'>$text</button>
        </div>";
}