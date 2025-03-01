<?php
use Slim\App;
use App\Application\Http\Controllers\Users\{RegisterUser, LoginUser, UpdateProfile};

return function (App $app) {
    // Add global middleware
    $app->add(\App\Infrastructure\Middleware\LoggingMiddleware::class);

    // Root route
    $app->get('/', function (\Psr\Http\Message\ServerRequestInterface $request, \Psr\Http\Message\ResponseInterface $response) {

        $response->getBody()->write(json_encode(['message' =>  config('app_name')]));
        return $response->withHeader('Content-Type', 'application/json; charset=UTF-8');
    });

    // Register user route
    $app->post('/register', RegisterUser::class);

    // Login user route
    $app->post('/login', LoginUser::class);

    // Protected route with AuthMiddleware
    $app->put('/profile', UpdateProfile::class)->add(\App\Infrastructure\Middleware\AuthMiddleware::class);
};