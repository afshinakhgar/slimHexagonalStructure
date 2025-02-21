<?php
use App\Bootstrap\AppSetup;

require __DIR__ . '/../vendor/autoload.php';

// Get the Slim app instance
$app = AppSetup::getApp();

// Run the application
$app->run();