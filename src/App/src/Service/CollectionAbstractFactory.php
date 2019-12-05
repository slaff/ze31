<?php

namespace App\Service;

use App\Service\XdevService;
use Zend\ServiceManager\Factory\AbstractFactoryInterface;
use Interop\Container\ContainerInterface;

class CollectionAbstractFactory implements AbstractFactoryInterface
{
	public function canCreate(ContainerInterface $container, $requestedName)
	{
		return preg_match('/(\w+)Collection$/', $requestedName);
	}
	
	public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
	{
		// Actually create this requested collection
		$config = $container->get('config');
		$dbSession = $container->get(XDevService::class);
		
		$collectionName = preg_replace('/Collection$/', '', $requestedName);
		
		return $dbSession->getSchema($config['db']['database_name'])->getCollection($collectionName);
	}
}