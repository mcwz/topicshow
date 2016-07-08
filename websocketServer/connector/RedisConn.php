<?php

namespace connector;

use tools\Session;

class RedisConn {

    private $manager;

    public function __construct($config) {
        $this->manager = new \redis();
        $this->manager->connect($config['redisHost'], $config['redisPort']);
    }

    public function save($key, $value, $expire = 1210) {
        $this->manager->set($key, $value, $expire);
    }

    public function saveFdSession($fd, $phpSessionId) {
        $this->save("fd:" . $fd, $phpSessionId);
    }

    public function refreshFdSession($fd) {
        $this->manager->expire("fd:" . $fd, 1210);
    }

    public function removeFdSession($fd) {
        $this->manager->del("fd:" . $fd);
    }

    public function getPhpSessId($fd) {
        return $this->manager->get("fd:" . $fd);
    }

    public function session($fd = 0) {
        if ($fd > 0) {
            $phpSessId = $this->getPhpSessId($fd);
            if ($phpSessId && $phpSessId != "") {
                $session = $this->manager->get('PHPREDIS_SESSION:' . $this->getPhpSessId($fd));
                return Session::unserialize($session);
            }
        }
        return array();
    }

}
