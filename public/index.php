<?php
use DI\Container;
use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';

// Load configuration
$container = require __DIR__ . '/../src/Bootstrap/Dependencies/dependencies.php';
AppFactory::setContainer($container);

// Create Slim app
$app = AppFactory::create();

// Load routes
$routing = require __DIR__ . '/../src/Routes/web.php';
$routing($app);

// Run the application
$app->run();
