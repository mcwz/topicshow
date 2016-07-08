<?php
namespace controllers;

use core\ControllerBase;

class User extends ControllerBase
{
	public static function info($objects)
	{
		$redisConn=$objects['redisConn'];
		$server=$objects['server'];
		$frame=$objects['frame'];
		$session=$redisConn->session($frame->fd);
		$message=$frame->data;
                
		//$server->push($frame->fd,);
	}
}