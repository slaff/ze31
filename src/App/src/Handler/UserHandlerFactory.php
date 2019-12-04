<?php

declare(strict_types=1);

namespace App\Handler;

use Psr\Container\ContainerInterface;
use App\Service\XdevService;

class UserHandlerFactory
{
    public function __invoke(ContainerInterface $container) : UserHandler
    {
    	$config = $container->get('config');
    	$dbSession = $container->get(XDevService::class);
    	return new UserHandler($dbSession->getSchema($config['db']['database_name'])->getCollection('user'));
    }
}
