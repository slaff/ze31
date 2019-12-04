<?php

declare(strict_types=1);

namespace App\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\Response\JsonResponse;

class AuthMiddleware implements MiddlewareInterface
{
	/**
	 * @var array
	 */
	private $config;
	
	public function __construct(array $config)
	{
		$this->config = $config;
	}
	
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler) : ResponseInterface
    {
    	// Authentication -> If X-Inpeco-Auth == 42 => 
    	
    	// TODO: get the X-Inpeco-Auth header from the request
    	// 		Check if it is 42 
    	$headers = $request->getHeader('X-Inpeco-Auth');
    	if(!empty($headers) && $headers[0] == "42") {
    		$request = $request->withAttribute('version', $this->config['version']);
    		return $handler->handle($request);
    	}
    	
    	$response = new JsonResponse(["auth"=> false]);
    	$response = $response->withStatus(403);
    	return $response;
    }
}
