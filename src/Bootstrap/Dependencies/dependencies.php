<?php
use DI\ContainerBuilder;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;


$builder = new ContainerBuilder();

(new \App\Bootstrap\Providers\DatabaseProvider())($builder);
(new \App\Bootstrap\Providers\RepositoryProvider())($builder);
(new \App\Bootstrap\Providers\ServiceProvider())($builder);
(new \App\Bootstrap\Providers\LoggerProvider())($builder);
(new \App\Bootstrap\Providers\ConfigProvider())($builder);
(new \App\Bootstrap\Providers\RedisProvider())($builder);
$container = $builder->build();


return $container;