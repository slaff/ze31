<?php

namespace App\Service;

use Psr\Container\ContainerInterface;

class XdevServiceFactory {
	public function __invoke( ContainerInterface $container ) 
	{
		$config = $container->get( "config" );

		$connectionString = 'mysqlx://' . $config["db"]['username'] . ':' . $config["db"]["password"] . '@' . $config["db"]["host"];
		return \mysql_xdevapi\getSession( $connectionString );
	}
}