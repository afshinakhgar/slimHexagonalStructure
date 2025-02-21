<?php
namespace App\Bootstrap\Providers;

use DI\ContainerBuilder;
use App\Ports\UserRepository;
use App\Infrastructure\MySqlUserRepository;

class RepositoryProvider
{
    public function __invoke(ContainerBuilder $builder): void
    {
        $builder->addDefinitions([
            UserRepository::class => \DI\autowire(MySqlUserRepository::class),
        ]);
    }
}
