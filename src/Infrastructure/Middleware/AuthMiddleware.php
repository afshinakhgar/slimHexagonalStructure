<?php
namespace App\Infrastructure\Middleware;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class AuthMiddleware implements MiddlewareInterface
{
    private string $jwtSecret;

    public function __construct($container)
    {
        // Get JWT secret from the container
        $this->jwtSecret = $container->get('config')['jwtSecret'];
    }


    private function unauthorizedResponse(): Response
    {
        $response = new \Slim\Psr7\Response();
        $response->getBody()->write(json_encode(['error' => 'Unauthorized']));
        return $response->withHeader('Content-Type', 'application/json; charset=UTF-8')->withStatus(401);
    }

    public function process(Request $request, RequestHandlerInterface $handler): Response
    {
        $authHeader = $request->getHeaderLine('Authorization');
        if (!$authHeader || !str_starts_with($authHeader, 'Bearer ')) {
            return $this->unauthorizedResponse();
        }

        $token = substr($authHeader, 7);
        try {
            $decoded = JWT::decode($token, new Key($this->jwtSecret, 'HS256'));
            $request = $request->withAttribute('user_id', $decoded->sub);
            return $handler->handle($request);
        } catch (\Exception $e) {
            return $this->unauthorizedResponse();
        }
        // TODO: Implement process() method.
    }
}