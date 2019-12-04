<?php

declare(strict_types=1);

namespace App\Handler;

use Psr\Container\ContainerInterface;
use App\Service\XdevService;

class PingHandlerFactory
{
    public function __invoke(ContainerInterface $container) : PingHandler
    {
	    $config = $container->get("config");	    
	    $dbSession = $container->get( XdevService::class);
    	
        return new PingHandler($config, $dbSession);
    }
}
