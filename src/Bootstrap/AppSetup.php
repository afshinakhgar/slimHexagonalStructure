<?php
namespace App\Bootstrap;

use DI\ContainerBuilder;
use Slim\Factory\AppFactory;

class AppSetup
{
    public static function getApp()
    {
        // Load environment variables from .env file
        $dotenv = \Dotenv\Dotenv::createImmutable(__DIR__ . '/../..');
        $dotenv->load();

        // Create container
        $container = require __DIR__ . '/Dependencies/dependencies.php';
        AppFactory::setContainer($container);

        // Create Slim app
        $app = AppFactory::create();

        // Load routes from src/Routes/web.php
        $routes = require __DIR__ . '/../Routes/web.php';
        $routes($app);

        return $app;
    }
}