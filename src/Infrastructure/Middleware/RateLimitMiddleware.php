<?php
namespace App\Infrastructure\Middleware;

use App\Application\Services\Core\ValidatorService;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Redis;
use Slim\Psr7\Response;

class RateLimitMiddleware implements MiddlewareInterface
{
    private Redis $redis;
    private int $maxRequests;
    private int $timeWindow;

    public function __construct(Redis $redis, int $maxRequests, int $timeWindow)
    {
        $this->redis = $redis;
        $this->maxRequests = $maxRequests;
        $this->timeWindow = $timeWindow;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        ValidatorService::validateIp($request);

        $ip = $request->getServerParams()['REMOTE_ADDR'] ?? 'unknown';
        $key = "rate_limit:{$ip}";

        $currentCount = (int) ($this->redis->get($key) ?: 0);

        if ($currentCount >= $this->maxRequests) {
            return $this->createTooManyRequestsResponse();
        }

        // استفاده صحیح از multi() و دستورات Redis
        $this->redis->multi();
        $this->redis->incr($key);
        $this->redis->expire($key, $this->timeWindow);
        $this->redis->exec();

        return $handler->handle($request);
    }

    private function createTooManyRequestsResponse(): ResponseInterface
    {


        $response = new Response(429);
        $response->getBody()->write(json_encode([
            'error' => 'Rate limit exceeded',
            'message' => "You have exceeded the {$this->maxRequests} requests per " . ($this->timeWindow / 60) . " minutes limit."
        ]));

        return $response->withHeader('Content-Type', 'application/json');
    }
}
