<?php

namespace App\Ports;


interface RedisInterface
{
    public function get(string $key): mixed;
    public function multi(): self;
    public function incr(string $key): int;
    public function expire(string $key, int $ttl): bool;
    public function exec(): mixed;
}