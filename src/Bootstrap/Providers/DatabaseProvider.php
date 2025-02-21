<?php
namespace App\Bootstrap\Providers;

use DI\ContainerBuilder;
use PDO;

class DatabaseProvider
{
    public function __invoke(ContainerBuilder $builder): void
    {
        $builder->addDefinitions([
            PDO::class => function ($container) {
                $config = $container->get('config')['db'];
                $pdo = new PDO(
                    $config['dsn'],
                    $config['username'],
                    $config['password'],
                    [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4"
                    ]
                );
                return $pdo;
            },
        ]);
    }
}