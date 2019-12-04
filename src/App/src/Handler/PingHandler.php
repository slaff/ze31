<?php

declare(strict_types=1);

namespace App\Handler;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\Response\JsonResponse;

use function time;
use Zend\Expressive\Router\RouteResult;

class PingHandler implements RequestHandlerInterface
{
	/**
	 * @var array
	 */
	private $config;
	
	/**
	 * @var \mysql_xdevapi\Session
	 */
	private $dbSession;
	
	public function __construct($config, $dbSession) {
		$this->config = $config;
		$this->dbSession  = $dbSession;
	}
	
    public function handle(ServerRequestInterface $request) : ResponseInterface
    {	
    	$headers = $request->getHeader('X-Inpeco-Action');
    	
    	/**
    	 * @var RouteResult $routeResult
    	 */
    	$routeResult = $request->getAttribute(RouteResult::class);
    	$name = $routeResult->getMatchedRouteName();
    	
    	
    	// TODO: Add in the response the name of the matched route
    	$response = new JsonResponse(['ack' => time(), 'config' =>  $this->config ]);
    	
    	// if X-Inpeco-Action =='print'
    	if(in_array('print', (array)$headers)) {
    		// add header in the response X-Inpeco-Print: hello
    		
    		$response = $response->withHeader('X-Inpeco-Print', 'hello');
    	}
    	
    	
        return $response;
    }
}
