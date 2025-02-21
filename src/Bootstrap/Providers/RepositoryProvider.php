<?php
namespace App\Bootstrap\Providers;

use App\Ports\UserRepository;
use DI\ContainerBuilder;
use App\Infrastructure\Repositories\MySql\MySqlUserRepository;

class RepositoryProvider
{
    public function __invoke(ContainerBuilder $builder): void
    {
        $builder->addDefinitions([
            UserRepository::class => \DI\autowire(MySqlUserRepository::class),
        ]);
    }
}