<?php

namespace App\Infrastructure;

use Configula\ConfigFactory;

class ConfigLoader
{
    private $config;

    public function __construct(string $configPath)
    {
        $this->config = ConfigFactory::loadPath($configPath);
    }

    public function get(string $key, $default = null)
    {
        return $this->config->get($key, $default);
    }

    public function all(): array
    {
        return $this->config->getItems();
    }
}