<?php 

namespace App\Service;

use Psr\Container\ContainerInterface;

class XdevServiceFactory 
{
	/**
	 * 
	 * @param ContainerInterface $container
	 * @return \mysql_xdevapi\getSession
	 */
	public function __invoke(ContainerInterface $container)	: \mysql_xdevapi\Session
	{
		$config = $container->get('config');
		
		$connectionString = "mysqlx://".$config['username'].":".$config['password']."@".$config["host"];
		return \mysql_xdevapi\getSession($connectionString);
	}
}