<?php

namespace App\Service;

use Psr\Container\ContainerInterface;

class XdevServiceFactory 
{
	/**
	 * 
	 * @param ContainerInterface $container
	 * @return \mysql_xdevapi\Schema
	 */
	public function __invoke(ContainerInterface $container)	: \mysql_xdevapi\Schema
	{
		$config = $container->get('config');
                $config = $config["db"];
		
		$connectionString = "mysqlx://".$config['username'].":".$config['password']."@".$config["host"];
		$dbSession = \mysql_xdevapi\getSession($connectionString);
		return $dbSession->getSchema($config['database_name']);
	}
}