<?php

declare(strict_types = 1);

namespace App\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\Response\JsonResponse;

class AuthMiddleware implements MiddlewareInterface {

	/**
	 * @var array
	 */
	private $config = array();

	public function __construct( array $config ) {
		$this->config = $config;
	}

	public function process( ServerRequestInterface $request, RequestHandlerInterface $handler ): ResponseInterface {
		$headers = $request->getHeader( "X-Inpeco-Auth" );
		if ( in_array( "42", $headers ) ) {
			$request = $request->withAttribute( "version", $this->config["version"] );
			$response = $handler->handle( $request );
		} else {
			$response = new JsonResponse( ["auth" => false] );
			$response = $response->withStatus( 403 );
		}
		return $response;
	}
}
