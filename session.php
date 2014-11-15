<?php
/**
 * Created by PhpStorm.
 * User: taras
 * Date: 14.11.14
 * Time: 23:03
 */

class Session implements SessionHandlerInterface {

    private $memcached;

    public function __construct()
    {
        $this->memcached = new Memcached();
        $this->memcached->addServer('localhost', 11211);
    }

    public function read($session_id)
    {
        $bag = $this->memcached->get($session_id);
        return $bag ? $bag : '';
    }

    public function write($session_id, $bag)
    {
        $this->memcached->set($session_id, $bag);
    }

    public function destroy($session_id)
    {
        $this->memcached->delete($session_id);
    }

    public function open($save_path, $session_id)
    {
        return true;
    }

    public function close()
    {
        return true;
    }

    public function gc($maxlifetime)
    {
        return true;
    }
}

$session = new Session();
session_set_save_handler($session);
if (isset($_COOKIE['salt']))
    session_id($_COOKIE['salt']);

session_start();