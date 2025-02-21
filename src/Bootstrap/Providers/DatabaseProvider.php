<?php
namespace App\Bootstrap\Providers;

use PDO;
use DI\ContainerBuilder;

class DatabaseProvider
{
    public function __invoke(ContainerBuilder $builder): void
    {
        $config = require __DIR__ . '/../../config/config.php';

        $builder->addDefinitions([
            PDO::class => function () use ($config) {
                return new PDO(
                    $config['db']['dsn'],
                    $config['db']['username'],
                    $config['db']['password'],
                    [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4"
                    ]
                );
            },
        ]);
    }
}
