<?php
$mc = new Memcached();
$mc->addServer('localhost', 11211);

echo '<a href="info.php">phpinfo</a>';

$keys = $mc->getAllKeys();

if (!$keys)
    die("No records found");

if(isset($_GET['delete']))
    $mc->delete($_GET['delete']);

session_start();
foreach($keys as $key) {
    $data = $mc->get($key);
    session_decode($data);
    $data = $_SESSION;
    $name = isset($data['email']) ? $data['email'] : $key;
    echo "<div><a href='memcached.php?delete=$key'>$name</a>";
    foreach($data as $k => $v)
        echo " $k=$v ";
    echo "</div>";
}