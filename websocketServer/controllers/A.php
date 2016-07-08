<?php
namespace controllers;

use core\ControllerBase;

class A extends ControllerBase
{
	public static function b($objects)
	{
		$redisConn=$objects['redisConn'];
		$server=$objects['server'];
		$frame=$objects['frame'];
		$session=$redisConn->session($frame->fd);
		print_r($session);
		$server->push($frame->fd,$frame->data);
	}
}