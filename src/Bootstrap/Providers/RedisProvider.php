<?php
namespace App\Bootstrap\Providers;

use DI\ContainerBuilder;
use Redis;

class RedisProvider
{
    public function __invoke(ContainerBuilder $builder): void
    {
        $builder->addDefinitions([
            Redis::class => function ($container) {
                // گرفتن تنظیمات از کانفیگ
                $config = config('redis') ?? [];

                $redis = new Redis();
                $redis->connect(
                    $config['host'] ?? '127.0.0.1',
                    $config['port'] ?? 6379,
                    $config['timeout'] ?? 0.0
                );

                if (!empty($config['password'])) {
                    $redis->auth($config['password']);
                }

                $redis->select($config['database'] ?? 0);

                return $redis;
            },
        ]);
    }
}