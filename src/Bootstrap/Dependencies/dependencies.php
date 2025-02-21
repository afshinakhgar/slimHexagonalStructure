<?php
use DI\ContainerBuilder;

$builder = new ContainerBuilder();
require __DIR__ . '/../Providers/ServiceProvider.php';
(new \App\Bootstrap\Providers\ServiceProvider())($builder);

return $builder->build();
