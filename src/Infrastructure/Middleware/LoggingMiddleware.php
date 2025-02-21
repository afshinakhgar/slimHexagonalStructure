<?php
namespace App\Infrastructure\Middleware;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Monolog\Logger;

class LoggingMiddleware
{
    private Logger $logger;

    public function __construct(Logger $logger)
    {
        $this->logger = $logger;
    }

    public function __invoke(Request $request, RequestHandler $handler): Response
    {
        // Log the request method and URI
        $this->logger->info("Request received: {$request->getMethod()} {$request->getUri()}");

        // Pass the request to the next middleware
        $response = $handler->handle($request);

        // Log the response status code
        $this->logger->info("Response sent: {$response->getStatusCode()}");

        return $response;
    }
}