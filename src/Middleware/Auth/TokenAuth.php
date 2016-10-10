<?php

namespace Slim\Middleware\Auth;

use Illuminate\Database\Query\Builder;
use FastRoute\Dispatcher\MarkBased;
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use \Carbon\Carbon;

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

    public function __invoke(Request $request, Response $response, $next)
    {
        $route = $request->getAttribute('route');
        if($route){
            $name = $route->getName();
            $methods = $route->getMethods();
            $arguments = $route->getArguments();
        }
        $path = rtrim($request->getUri()->getPath());
        $httpMethod = $request->getMethod();
        $settings = $this->settings = $this->ci->get('settings')['auth'];
        $table = $this->ci->get('db')->table($settings['table']);
        $token = $request->getQueryParam('token');

        $user = $table->where('token',$token)->first();

        if($user){
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

    public function authenticate(Request $request){
        if(!$request->isPost()){
            return false;
        }
        
        $fields = $this->settings['fields'];

        $body = $request->getParsedBody();
        if(!isset($body[$fields['username']]) || empty($body[$fields['username']]) || !isset($body[$fields['password']]) || empty($body[$fields['password']]))
            return false;

        $body[$fields['password']] = $this->hash($body[$fields['password']]);

        $user = $this->table
                    ->where($fields['username'],$body[$fields['username']])
                    ->where($fields['password'],$body[$fields['password']])
                    ->first();

        if(!$user)
            return false;

        $user->token = sha1(uniqid());
        $user->token_created = Carbon::now();

        if($this->table->update((array)$user))
            return (array)$user;

        return false;
    }

    public function hash($password = null){
        if(!$password)
            return null;
        $salt = $this->ci->get('settings')['auth']['salt'];
        return password_hash($password,PASSWORD_BCRYPT,compact('salt'));
    }

    public static function passwordHash($password,$salt){
        return password_hash($password,PASSWORD_BCRYPT,compact('salt'));
    }

    public function __construct($ci){
        $this->ci = $ci;
        $this->settings = $this->ci->get('settings')['auth'];
        $this->table = $this->ci->get('db')->table($this->settings['table']);
    }


}