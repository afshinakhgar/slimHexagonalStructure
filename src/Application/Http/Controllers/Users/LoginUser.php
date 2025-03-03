<?php
namespace App\Application\Http\Controllers\Users;

use App\Application\Services\User\AuthService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class LoginUser
{
    private AuthService $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function __invoke(Request $request, Response $response): Response
    {
        $data = $request->getParsedBody();

        $token = $this->authService->login($data['email'], $data['password']);
        if ($token) {
            $response->getBody()->write(json_encode(['token' => $token]));
            return $response->withHeader('Content-Type', 'application/json; charset=UTF-8');
        }
        $response->getBody()->write(json_encode(['error' => 'Invalid credentials']));
        return $response->withHeader('Content-Type', 'application/json; charset=UTF-8')->withStatus(401);
    }
}