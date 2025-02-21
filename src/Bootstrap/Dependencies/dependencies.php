<?php
use DI\ContainerBuilder;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;


$builder = new ContainerBuilder();

// Load configuration and store it in the container
$builder->addDefinitions([
    'config' => function () {
        return require __DIR__ . '/../../../config/config.php';
    },
]);

// Add Monolog logger
$builder->addDefinitions([
    Logger::class => function () {
        $logger = new Logger('app_logger');
        $logger->pushHandler(new StreamHandler(__DIR__ . '/../../storage/logs/app.log', Logger::DEBUG));
        return $logger;
    },
]);

$builder->addDefinitions([
    \App\Infrastructure\Middleware\LoggingMiddleware::class => \DI\autowire(),
]);

// Autoload all providers
require_once __DIR__ . '/../Providers/DatabaseProvider.php';
(new \App\Bootstrap\Providers\DatabaseProvider())($builder);

require_once __DIR__ . '/../Providers/RepositoryProvider.php';
(new \App\Bootstrap\Providers\RepositoryProvider())($builder);

require_once __DIR__ . '/../Providers/ServiceProvider.php';
(new \App\Bootstrap\Providers\ServiceProvider())($builder);

//return $builder->build();
return $builder->build();