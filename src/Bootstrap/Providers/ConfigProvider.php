<?php

namespace App\Bootstrap\Providers;

use App\Infrastructure\ConfigLoader;
use DI\ContainerBuilder;

class ConfigProvider
{
    public function __invoke(ContainerBuilder $builder): void
    {
        $builder->addDefinitions([
            'config' => \DI\autowire(ConfigLoader::class),
        ]);
    }
}