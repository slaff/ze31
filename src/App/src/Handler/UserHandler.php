<?php

declare(strict_types=1);

namespace App\Handler;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\Response;
use Zend\Diactoros\Response\JsonResponse;
use Zend\Form\Annotation\AnnotationBuilder;
use App\Entity\UserEntity;

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
    		// TODO: filter the data...
    		
    		$inputData = $request->getQueryParams();
    		$searchCondition = '';
    		$bindData = [];
    		foreach ($inputData as $key => $value) {
    			// TODO: escape the key name
    			$bindKey = str_replace('.', '_', $key);
    			$searchCondition .= "$key = :$bindKey AND ";
    			$bindData[$bindKey] = $value;
    		}
    		if(strlen($searchCondition)) {
    			$searchCondition = substr($searchCondition, 0, -4 );
    		}
    		$searchCondition = trim($searchCondition);
    		
    		$data = $this->userCollection->find($searchCondition)
    									 ->bind($bindData)
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
    		return (new JsonResponse(['success' => false]))->withStatus(412);
    	}
    	
    	$this->userCollection->removeOne($id);
    	
    	return new JsonResponse(['success' => true]);
    }
    
    
    public function postAction(ServerRequestInterface $request): ResponseInterface
    {
    	$inputData = $request->getParsedBody();	
    	
    	// filter the data...
    	
    	$builder = new AnnotationBuilder();
    	$entity = new UserEntity();
    	$form = $builder->createForm($entity);
    	
    	$form->bind($entity);
    	$form->setData($inputData);
    	if(!$form->isValid()) {
    		$data  = [
    			'success' => false,
    			'error' => $form->getMessages(),
    		];
    		
    		return (new JsonResponse($data))->withStatus(412);
    	}
    	
    	$inputData = $form->getData();
    	$inputData = $form->getHydrator()->extract($inputData); // this will get back the data as array
    	
    	$result = $this->userCollection->add($inputData)->execute();
    	$id = $result->getGeneratedIds();
    	
    	return (new JsonResponse($id))->withStatus(201);
    }
    
    public function patchAction(ServerRequestInterface $request): ResponseInterface
    {
    	$id = $request->getAttribute('id');
    	if($id === null) {
    		return (new JsonResponse(['success' => false]))->withStatus(412);
    	}
    	
    	$inputData = $request->getParsedBody();	
    	
    	$builder = new AnnotationBuilder();
    	$entity = new UserEntity();
    	$form = $builder->createForm($entity);
    	
    	$form->setValidationGroup(array_keys($inputData));
    	
    	$form->bind($entity);
    	$form->setData($inputData);
    	if(!$form->isValid()) {
    		$data  = [
    				'success' => false,
    				'error' => $form->getMessages(),
    		];
    		
    		return (new JsonResponse($data))->withStatus(412);
    	}
    	
    	$inputData = $form->getData();
    	$inputData = $form->getHydrator()->extract($inputData); // this will get back the data as array
    	
    	
    	$result = $this->userCollection->modify('_id = :id')
    	                               ->bind(['id' => $id])
    	                               ->patch(json_encode($inputData))
    	                               ->execute();
    	
    	$changes = $result->getAffectedItemsCount();
    	return new JsonResponse([ 'id' => $id, 'changes'=> $changes]);
    }
}
