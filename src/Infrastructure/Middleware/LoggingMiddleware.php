<?php
namespace App\Infrastructure\Middleware;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

class LoggingMiddleware
{
    public function __invoke(Request $request, RequestHandler $handler): Response
    {
        echo "LoggingMiddleware executed.\n";
        return $handler->handle($request);
    }
}
