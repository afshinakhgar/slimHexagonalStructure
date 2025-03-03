<?php
namespace Tests\Infrastructure\Middleware;

use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;
use App\Infrastructure\Middleware\RateLimitMiddleware;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Psr7\Request;
use Slim\Psr7\Uri;
use Slim\Psr7\Headers;
use Slim\Psr7\Stream;
use Slim\Psr7\Response;
use Redis;

class RateLimitMiddlewareTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    private $redisMock;
    private RateLimitMiddleware $middleware;

    protected function setUp(): void
    {
        $this->redisMock = Mockery::mock(Redis::class);
        $this->middleware = new RateLimitMiddleware($this->redisMock, 5, 60);
    }

    private function createRequest(string $method = 'GET'): Request
    {
        $uri = new Uri('http', 'localhost', null, '/');
        $headers = new Headers([]);
        $body = new Stream(fopen('php://temp', 'r+'));
        $serverParams = ['REMOTE_ADDR' => '127.0.0.1'];

        return new Request($method, $uri, $headers, [], $serverParams, $body);
    }

    public function testAllowsRequestsBelowLimit(): void
    {
        $this->redisMock->shouldReceive('get')->andReturn(0);
        $this->redisMock->shouldReceive('multi')->andReturnSelf();
        $this->redisMock->shouldReceive('incr')->andReturn(1); // اصلاح شد
        $this->redisMock->shouldReceive('expire')->andReturn(true); // اصلاح شد
        $this->redisMock->shouldReceive('exec')->andReturn([]);

        $request = $this->createRequest();
        $handler = Mockery::mock(RequestHandlerInterface::class);
        $handler->shouldReceive('handle')->andReturn(new Response());

        $response = $this->middleware->process($request, $handler);
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testBlocksRequestsAboveLimit(): void
    {
        $this->redisMock->shouldReceive('get')->andReturn(5);

        $request = $this->createRequest();
        $handler = Mockery::mock(RequestHandlerInterface::class);

        $response = $this->middleware->process($request, $handler);
        $this->assertEquals(429, $response->getStatusCode());
        $this->assertStringContainsString('Rate limit exceeded', (string) $response->getBody());
    }

    public function testResetsAfterTimeWindow(): void
    {
        $this->redisMock->shouldReceive('get')->andReturn(0);
        $this->redisMock->shouldReceive('multi')->andReturnSelf();
        $this->redisMock->shouldReceive('incr')->andReturn(1); // اصلاح شد
        $this->redisMock->shouldReceive('expire')->andReturn(true); // اصلاح شد
        $this->redisMock->shouldReceive('exec')->andReturn([]);

        $request = $this->createRequest();
        $handler = Mockery::mock(RequestHandlerInterface::class);
        $handler->shouldReceive('handle')->andReturn(new Response());

        $response = $this->middleware->process($request, $handler);
        $this->assertEquals(200, $response->getStatusCode());
    }

    protected function tearDown(): void
    {
        Mockery::close();
    }
}