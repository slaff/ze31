<?php

declare(strict_types=1);

namespace App\Handler;

use Psr\Container\ContainerInterface;


class UserHandlerFactory
{
    public function __invoke(ContainerInterface $container) : UserHandler
    {
    	return new UserHandler($container->get('userCollection'));
    }
}
