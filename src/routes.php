<?php
// Routes
// $app->any('/users[/{id}]', \App\Controller\UsersController::class);

$app->get('/users','\App\Controller\UsersController:index');
$app->get('/users/{id}','\App\Controller\UsersController:view');



$app->get('/[{name}]', function ($request, $response, $args) {
    // Sample log message
    $this->logger->info("Slim-Skeleton '/' route");

    // Render index view
    return $this->renderer->render($response, 'index.phtml', $args);
});

