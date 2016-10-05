<?php

namespace App\Controller;

use Slim\Views\Twig;
use Psr\Log\LoggerInterface;
use Illuminate\Database\Query\Builder;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class Controller
{
    private $logger;
    protected $table;

    public function __construct(
        LoggerInterface $logger,
        Builder $table
    ) {
        $this->logger = $logger;
        $this->table = $table;
    }

    // public function __invoke(Request $request, Response $response, $args){
        
    // }

    public function __invoke(ContainerInterface $ci){
        
    }
}