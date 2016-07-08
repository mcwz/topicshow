<?php

namespace core;

use swoole_websocket_server;
use connector\Mongo;
use connector\RedisConn;

class WebsocketServer {

    private $websocketServer;
    private $config;
    private $mongoManager;
    private $redisConn;
    private $topic;

    public function __construct($config) {
        $this->config = $config;
        $this->mongoManager = new Mongo($config);
        $this->redisConn = new RedisConn($config);
        $this->topic = new \connector\Topic($this->mongoManager);

        $this->websocketServer = new swoole_websocket_server($config['bind_ip'], $config['bind_port']);

        /* $this->websocketServer->set(array(
          'heartbeat_check_interval' => 5,
          'heartbeat_idle_time' => 10,
          )); */

        $this->websocketServer->on('open', function (swoole_websocket_server $server, $request) {
            $cookie = $request->cookie;
            if (isset($cookie['PHPSESSID'])) {
                $phpSessId = $cookie['PHPSESSID'];
                if ($phpSessId) {
                    $this->redisConn->saveFdSession($request->fd, $phpSessId);
                }
            }

            $server->push($request->fd, '["connected"]');

            if (DEBUG) {
                echo "server: handshake success with fd{$request->fd}\n";
            }
        });


        $this->websocketServer->on('close', function ($ser, $fd) {
            $this->redisConn->removeFdSession($fd);
            $this->topic->leaveAllTopic($fd);
            if (DEBUG) {
                echo "client {$fd} closed\n";
            }
        });

        
    }

    public function run() {

        $this->websocketServer->on('message', function (swoole_websocket_server $server, $frame) {

            $json = json_decode($frame->data);
            if($json===null && DEBUG){echo "[warn]message can't be json_decode \n";}
            if (DEBUG)
                echo "receive from {$frame->fd}:{$frame->data},opcode:{$frame->opcode},fin:{$frame->finish}\n";

            if (isset($json->route)) {
                if (DEBUG){echo "routing to $json->route \n";}
                $route_array = $this->_findController($json->route);
                $this->_load_controller($route_array, $server, $frame);
            }
        });

        $this->websocketServer->start();
    }

    private function _findController($route) {
        $config = $this->config;
        $route = trim($route);
        $route = trim($route, '/');
        if ($route === '')
            return '';
        $route_segment = explode('/', $route);
        $controller_base_path = $config['base_dir'] . '/controllers/';

        $class_file_path = $controller_base_path;
        $class = ucfirst($config['default_route_controller']);
        $function = lcfirst($config['default_route_function']);

        $needfind = "YYY";

        while (($route_segment_count = count($route_segment) > 0)) {
            if ($needfind[0] === 'Y') {
                if (is_dir($class_file_path . $route_segment[0])) {
                    $class_file_path.=$route_segment[0] . '/';
                    array_splice($route_segment, 0, 1);
                    continue;
                } else {
                    $needfind[0] = 'N';
                }
            }

            if ($needfind[1] === 'Y') {
                $class = ucfirst($route_segment[0]);
                array_splice($route_segment, 0, 1);
                $needfind[1] = 'N';
                continue;
            }


            if ($needfind[2] === 'Y') {
                $function = lcfirst($route_segment[0]);
                $needfind[2] = 'N';
                break;
            }
        }

        return array($class_file_path, $class, $function);
    }

    private function _load_controller($route_array, $server, $frame) {
        $class_file = $route_array[0] . $route_array[1] . '.php';
        if (file_exists($class_file)) {
            if (DEBUG)
                echo "Class file located in:$class_file \r\n";
            $class = "controllers\\" . $route_array[1];
            if (DEBUG)
                echo "Loading class $class \r\n";
            $controller = $class::getInstance();

            /*
              将可能用到的各种对象压入controller
             */
            $objects = [
                'websocketServer' => $this->websocketServer,
                'config' => $this->config,
                'mongoManager' => $this->mongoManager,
                'redisConn' => $this->redisConn,
                'server' => $server,
                'frame' => $frame,
                'topic'=>$this->topic
            ];

            call_user_func_array(array($controller, $route_array[2]), array($objects));
        }
        else {
            if (DEBUG)
                echo "class file $class_file not found.\n";
        }
    }

}
