<?php
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

$lang = file_get_contents('lang/ru.json');
$lang = json_decode($lang, true);

function t($message) {
    global $lang;
    return isset($lang[$message]) ? $lang[$message] : $message;
}

$db = connect();
$user = null;

if (isset($_COOKIE['user'])) {

}

$page = isset($_GET['go']) ? $_GET['go'] : 'home';

if (!in_array($page, ['home', 'login', 'signup']))
    $page = 'error';

function anchor($go) {
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