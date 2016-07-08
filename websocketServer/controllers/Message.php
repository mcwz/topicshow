<?php

namespace controllers;

use core\ControllerBase;

class Message extends ControllerBase {

    public static function talk($objects) {
        $redisConn = $objects['redisConn'];
        $server = $objects['server'];
        $frame = $objects['frame'];
        $topicObj = $objects['topic'];
        $session = $redisConn->session($frame->fd);
        $message = json_decode($frame->data);
        $message->data->from=$session['nikename'];
        $message->data->ctime=time();
        $topicClients = $topicObj->getTopicClient($message->data->topic);
        foreach ($topicClients as $client) {
            $server->push($client, json_encode($message));
            echo "sending data to $client .\n";
        }
    }

}
