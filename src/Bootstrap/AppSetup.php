<?php
namespace App\Bootstrap;

use DI\ContainerBuilder;
use Slim\Factory\AppFactory;

class AppSetup
{
    public static function getApp()
    {
        require_once __DIR__ . '/../../src/Helpers.php';

        // Load environment variables from .env file
        $dotenv = \Dotenv\Dotenv::createImmutable(__DIR__ . '/../..');
        $dotenv->load();
        global $container;

        // Create container
        $container = require __DIR__ . '/Dependencies/dependencies.php';
        AppFactory::setContainer($container);

        // Create Slim app
        $app = AppFactory::create();
        $app->addBodyParsingMiddleware();

        // Load routes from src/Routes/web.php
        $routes = require __DIR__ . '/../Routes/web.php';
        $routes($app);

        return $app;
    }
}