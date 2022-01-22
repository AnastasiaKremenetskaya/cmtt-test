<?php

use Doctrine\DBAL\DriverManager;

return DriverManager::getConnection([
    'dbname' => env('DB_DATABASE'),
    'user' => 'root',
    'password' => env('DB_PASSWORD'),
    'host' => 'db',
    'port' => env('DB_PORT'),
    'driver' => 'pdo_mysql',
]);
