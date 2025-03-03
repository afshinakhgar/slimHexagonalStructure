<?php
namespace App\Application\Http\Controllers\Users;

use App\Application\Services\User\AuthService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class RegisterUser
{
    private AuthService $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function __invoke(Request $request, Response $response): Response
    {
        $data = $request->getParsedBody();
        $user = $this->authService->register($data['name'], $data['email'], $data['password']);
        $response->getBody()->write(json_encode(['id' => $user->getId(), 'email' => $user->getEmail()]));
        return $response->withHeader('Content-Type', 'application/json; charset=UTF-8')->withStatus(201);
    }
}