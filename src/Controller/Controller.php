<?php

namespace App\Controller;

use Slim\Views\Twig;
use Psr\Log\LoggerInterface;
use Illuminate\Database\Query\Builder;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Interop\Container\ContainerInterface;

class Controller
{
    private $logger;
    protected $table;

    public function __construct(
        LoggerInterface $logger,
        Builder $table,
        ContainerInterface $ci
    ) {
        $this->logger = $logger;
        $this->table = $table;
        $this->ci = $ci;
        $this->auth = $this->ci->get('settings')['auth'];
        $this->Auth = new \Slim\Middleware\Auth\TokenAuth($ci);
    }

    public function __invoke(ContainerInterface $ci){
        $this->Auth = new Middleware\Auth\TokenAuth($ci);
    }
}