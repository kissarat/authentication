<?php
$db = new PDO("mysql:host=". getenv('IP') or 'localhost' .";dbname=c9", getenv('C9_USER') or 'kissarat');