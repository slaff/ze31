<?php

namespace App\Service;

use Psr\Container\ContainerInterface;

class XDevServiceFactory
{
	public function __invoke(ContainerInterface $container) : \mysql_xdevapi\Session
	{
		$config = $container->get('config');
		$config = $config['db'];
		$connectionString = "mysqlx://".$config['username'].":".$config['password']."@".$config['host'];
		$session = \mysql_xdevapi\getSession($connectionString);
		return $session;
	}
}
