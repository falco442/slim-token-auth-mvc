<?php

namespace Slim\Middleware\Auth;

use Illuminate\Database\Query\Builder;
use FastRoute\Dispatcher\MarkBased;

class TokenAuth
{
    /**
     * Example middleware invokable class
     *
     * @param  \Psr\Http\Message\ServerRequestInterface $request  PSR7 request
     * @param  \Psr\Http\Message\ResponseInterface      $response PSR7 response
     * @param  callable                                 $next     Next middleware
     *
     * @return \Psr\Http\Message\ResponseInterface
     */

    protected $ci;

    public function __invoke($request, $response, $next)
    {
        $route = $request->getAttribute('route');
        if($route){
            $name = $route->getName();
            $methods = $route->getMethods();
            $arguments = $route->getArguments();
        }
        $path = rtrim($request->getUri()->getPath());
        $httpMethod = $request->getMethod();
        $settings = $this->ci->get('settings')['auth'];
        $table = $this->ci->get('db')->table($settings['table']);
        $token = $request->getQueryParam('token');

        $user = $table->where('token',$token)->first();

        if($user){
            $request->withAttribute($settings['userIdField'],$user->id);
            $response = $next($request, $response);
            return $next($request,$response);
        }

        $result = [0];


        $dispatcher = \FastRoute\simpleDispatcher(function(\FastRoute\RouteCollector $r) use($settings,$path) {
            foreach($settings['allowed_routes'] as $method => $routes){
                foreach($routes as $routePath){
                    $r->addRoute($method, $routePath, 'handler0');
                }
            }
        });

        $routeInfo = $dispatcher->dispatch($httpMethod, $path);

        switch ($routeInfo[0]) {
            case \FastRoute\Dispatcher::NOT_FOUND:
                // ... 404 Not Found
                return $response->withJSON("Page not found");
                break;
            case \FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
                $allowedMethods = $routeInfo[1];
                // ... 405 Method Not Allowed
                return $response->withJSON("Method not allowed");
                break;
            case \FastRoute\Dispatcher::FOUND:
                $handler = $routeInfo[1];
                $vars = $routeInfo[2];
                // ... call $handler with $vars
                return $next($request,$response);
                break;
        }

        return $response->withStatus(403)->withJSON('Not allowed');
    }

    public function authenticate($request,$response){
        if(!$request->isPost()){
            return false;
        }

    }

    public function __construct($ci){
        $this->ci = $ci;
    }


}