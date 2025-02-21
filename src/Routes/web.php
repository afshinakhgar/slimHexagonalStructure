<?php
use Slim\App;
use App\Application\Http\Controllers\AuthController;

return function (App $app) {
    // Add global middleware
    $app->add(\App\Infrastructure\Middleware\LoggingMiddleware::class);

    // Root route
    $app->get('/', function (\Psr\Http\Message\ServerRequestInterface $request, \Psr\Http\Message\ResponseInterface $response) {
        $response->getBody()->write(json_encode(['message' => 'Welcome to the Hexagonal PHP API']));
        return $response->withHeader('Content-Type', 'application/json; charset=UTF-8');
    });

    // Register route
    $app->post('/register', [AuthController::class, 'register']);

    // Login route
    $app->post('/login', [AuthController::class, 'login']);

    // Protected route with AuthMiddleware
    $app->get('/protected', function (\Psr\Http\Message\ServerRequestInterface $request, \Psr\Http\Message\ResponseInterface $response) {
        $userId = $request->getAttribute('user_id');
        $response->getBody()->write(json_encode(['message' => "Hello user $userId"]));
        return $response->withHeader('Content-Type', 'application/json; charset=UTF-8');
    })->add(\App\Infrastructure\Middleware\AuthMiddleware::class);

    // Update profile route with AuthMiddleware
    $app->put('/profile', function (\Psr\Http\Message\ServerRequestInterface $request, \Psr\Http\Message\ResponseInterface $response, array $args) {
        $data = $request->getParsedBody();
        $userId = $request->getAttribute('user_id');
        $service = $GLOBALS['container']->get(\App\Application\Services\UpdateProfileService::class);
        $user = $service->execute($userId, $data['name']);
        $response->getBody()->write(json_encode(['id' => $user->getId(), 'name' => $user->getName()]));
        return $response->withHeader('Content-Type', 'application/json; charset=UTF-8');
    })->add(\App\Infrastructure\Middleware\AuthMiddleware::class);
};
