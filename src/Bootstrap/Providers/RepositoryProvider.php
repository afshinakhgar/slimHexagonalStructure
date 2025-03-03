<?php
namespace App\Bootstrap\Providers;

use App\Infrastructure\Repositories\MySql\MySqlUserRepository;
use App\Ports\Repository\UserRepository;
use DI\ContainerBuilder;

class RepositoryProvider
{
    public function __invoke(ContainerBuilder $builder): void
    {
        $builder->addDefinitions([
            UserRepository::class => \DI\autowire(MySqlUserRepository::class),
        ]);
    }
}