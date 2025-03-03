<?php
namespace App\Bootstrap\Providers;

use DI\ContainerBuilder;
use App\Infrastructure\Middleware\LoggingMiddleware;
use App\Infrastructure\Middleware\AuthMiddleware;
use App\Infrastructure\Middleware\RateLimitMiddleware;

class MiddlewareProvider
{
    public function __invoke(ContainerBuilder $builder): void
    {
        $builder->addDefinitions([
            LoggingMiddleware::class => \DI\autowire(),

            AuthMiddleware::class => \DI\autowire()
                ->constructorParameter('jwtSecret', 'your-secret-key'),

            RateLimitMiddleware::class => function ($container) {
                $config = $container->get('config')['rate_limit'] ?? [];
                return \DI\autowire(RateLimitMiddleware::class)
                    ->constructorParameter('maxRequests', $config['max_requests'] ?? 100)
                    ->constructorParameter('timeWindow', $config['time_window'] ?? 3600)
                    ->__invoke($container);
            },

            'middleware.stack' => [
                LoggingMiddleware::class,
                AuthMiddleware::class,
                RateLimitMiddleware::class,
            ],
        ]);
    }
}