<?php

declare(strict_types=1);

namespace App\Handler;

use Psr\Container\ContainerInterface;
use Zend\Expressive\Template\TemplateRendererInterface;
use App\Service\XdevService;

class UserHandlerFactory
{
    public function __invoke(ContainerInterface $container) : UserHandler
    {
    	$dbSession = $container->get(XdevService::class);
    	$userCollection = $dbSession->getCollection('user');
    	
    	return new UserHandler($userCollection );
    }
}
