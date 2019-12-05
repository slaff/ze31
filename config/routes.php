<?php

declare(strict_types=1);

use Psr\Container\ContainerInterface;
use Zend\Expressive\Application;
use Zend\Expressive\MiddlewareFactory;
use Zend\Expressive\Helper\BodyParams\BodyParamsMiddleware;

/**
 * Setup routes with a single request method:
 *
 * $app->get('/', App\Handler\HomePageHandler::class, 'home');
 * $app->post('/album', App\Handler\AlbumCreateHandler::class, 'album.create');
 * $app->put('/album/:id', App\Handler\AlbumUpdateHandler::class, 'album.put');
 * $app->patch('/album/:id', App\Handler\AlbumUpdateHandler::class, 'album.patch');
 * $app->delete('/album/:id', App\Handler\AlbumDeleteHandler::class, 'album.delete');
 *
 * Or with multiple request methods:
 *
 * $app->route('/contact', App\Handler\ContactHandler::class, ['GET', 'POST', ...], 'contact');
 *
 * Or handling all request methods:
 *
 * $app->route('/contact', App\Handler\ContactHandler::class)->setName('contact');
 *
 * or:
 *
 * $app->route(
 *     '/contact',
 *     App\Handler\ContactHandler::class,
 *     Zend\Expressive\Router\Route::HTTP_METHOD_ANY,
 *     'contact'
 * );
 */
return function (Application $app, MiddlewareFactory $factory, ContainerInterface $container) : void {
    $app->get('/', App\Handler\HomePageHandler::class, 'home');
    $app->get('/api/ping', App\Handler\PingHandler::class, 'api.ping');
    
    $app->get('/api/user',  [
    						App\Middleware\GetParamsMiddleware::class,
    						App\Handler\UserHandler::class
						    ], 'user.list');
    
    $app->post('/api/user', [
			    		BodyParamsMiddleware::class,
			    		App\Handler\UserHandler::class,
			    ],'user.post');
    
    // TODO: updating users after the brake....
    
    $app->patch('/api/user/:id', [
								    BodyParamsMiddleware::class,
								    App\Handler\UserHandler::class,
								  ],'user.patch');
    
    
    $app->get('/api/user/:id', App\Handler\UserHandler::class, 'user.get');
    $app->delete('/api/user/:id', App\Handler\UserHandler::class, 'user.delete');
};
