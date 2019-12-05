<?php

declare(strict_types=1);

namespace App\Handler;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\Response;
use Zend\Diactoros\Response\JsonResponse;

class UserHandler implements RequestHandlerInterface
{

	/**
	 * @var \mysql_xdevapi\Collection
	 */
	private $userCollection ;
	
	public function __construct($userCollection )
    {
    	$this->userCollection  = $userCollection ;
		
		
    }

    public function handle(ServerRequestInterface $request) : ResponseInterface
    {
        $method = $request->getMethod();
        
        $methodName = $method.'Action';
        
        if(method_exists($this, $methodName)) {
        	return $this->$methodName($request);
        }
        
        return (new Response())->withStatus(404);
    }
    
    
    public function getAction(ServerRequestInterface $request): ResponseInterface
    {
    	$id = $request->getAttribute('id');
    	
    	if($id === null) {
    		$data = $this->userCollection->find()
    									 ->execute()
    									 ->fetchAll();
    	}
    	else {
    		$data = $this->userCollection->find(' _id = :id')
    										->bind(['id' => $id])
								    		->execute()
								    		->fetchOne();
    		
    	}
    	
    	
    	$response = new JsonResponse($data);
    	
    	if(empty($data)) {
    		$response = $response->withStatus(404);
    	}
    	
    	return $response;
    }
    
    public function deleteAction(ServerRequestInterface $request): ResponseInterface
    {
    	
    	$id = $request->getAttribute('id');
    	if($id === null) {
    		// TODO: Precondition failed
    		return (new JsonResponse(['success' => false]))->withStatus(412);
    	}
    	
    	$this->userCollection->removeOne($id);
    	
    	return new JsonResponse(['success' => true]);
    }
    
    
    public function postAction(ServerRequestInterface $request): ResponseInterface
    {
    	$inputData = $request->getParsedBody();	
    	
    	// TODO: filter the data...
    	
    	$result = $this->userCollection->add($inputData)->execute();
    	$id = $result->getGeneratedIds();
    	
    	return (new JsonResponse($id))->withStatus(201);
    }
    
}
