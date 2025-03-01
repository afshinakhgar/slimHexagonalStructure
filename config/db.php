<?php
return [
    'db' => [
        'dsn' => $_ENV['DB_DSN'],
        'username' => $_ENV['DB_USERNAME'],
        'password' => $_ENV['DB_PASSWORD'],
    ],
    'jwtSecret' => 'your-secret-key', // Add JWT secret here

];

