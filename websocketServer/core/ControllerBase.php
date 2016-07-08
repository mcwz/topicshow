<?php

namespace core;

class ControllerBase {

    protected static $_instance;

    protected function __construct() {
        
    }

    protected function __clone() {
        
    }

    public static function getInstance() {
        if (!(static::$_instance instanceof static)) {
            static::$_instance = new static();
        }
        return static::$_instance;
    }

}
