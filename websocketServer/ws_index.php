<?php

defined('DEBUG') or define('DEBUG', true);
$config = require(__DIR__ . '/config.php');
require(__DIR__ . '/vendor/autoload.php');



(new core\WebsocketServer($config))->run();
