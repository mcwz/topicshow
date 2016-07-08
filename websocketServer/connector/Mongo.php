<?php
namespace connector;

class Mongo{
	private $manager;

	public function __construct($config)
	{
		$this->manager = new \MongoDB\Driver\Manager($config['mongodbConnectStr']);  
	}

	public function getManager()
	{
            return $this->manager;
	}
}