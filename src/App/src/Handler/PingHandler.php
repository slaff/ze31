<?php

declare(strict_types = 1);

namespace App\Handler;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\Response\JsonResponse;

use function time;
use Zend\Expressive\Router\RouteResult;

class PingHandler implements RequestHandlerInterface {

	/**
	 * @var array
	 */
	private $config = array();
	private $dbSession;

	public function __construct( array $config, $dbSession ) {
		$this->config = $config;
		$this->dbSession = $dbSession;
	}

	public function handle( ServerRequestInterface $request ): ResponseInterface {
		$headers = $request->getHeader( "X-Inpeco-Action" );

		/**
		 * @var RouteResult $routeResult
		 */
		$routeResult = $request->getAttribute( RouteResult::class );
		$routeName = $routeResult->getMatchedRouteName();

		$response = new JsonResponse( [
			                              'ack' => time(),
			                              'headers' => $headers,
			                              'matchedRoute' => $routeName,
			                              'version' => $this->config["version"]
		                              ] );

		if ( in_array( 'print', $headers ) ) {
			$response = $response->withHeader( "X-Inpeco-Header", "hello" );
		}

		return $response;
	}
}
