<?php
// Routes


$app->any('/login', '\App\Controller\UsersController:login');

$app->get('/users','\App\Controller\UsersController:index');
$app->get('/users/{id}','\App\Controller\UsersController:view');
$app->post('/users','\App\Controller\UsersController:add');



// $app->get('/[{name}]', function ($request, $response, $args) {
//     // Sample log message
//     $this->logger->info("Slim-Skeleton '/' route");

//     // Render index view
//     return $this->renderer->render($response, 'index.phtml', $args);
// });

