<?php

declare(strict_types=1);

namespace App\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class GetParamsMiddleware implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler) : ResponseInterface
    {
    	if(!empty($_SERVER['QUERY_STRING'])) {
    		$request = $request->withQueryParams($this->parseQueryString($_SERVER['QUERY_STRING']));
    	}
    	
        return $handler->handle($request);
    }
    
    private function parseQueryString($data)
    {
    	$data = preg_replace_callback('/(?:^|(?<=&))[^=[]+/', function($match) {
    		return bin2hex(urldecode($match[0]));
    	}, $data);
    		
    		parse_str($data, $values);
    		
    		return array_combine(array_map('hex2bin', array_keys($values)), $values);
    }
}
