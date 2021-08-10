<?php

return [
    'class' => 'yii\db\Connection',
    'dsn' => 'mysql:host=' .
        env('DATABASE_HOST') . ';port='   .
        env('DATABASE_PORT') . ';dbname=' .
        env('DATABASE_NAME'),
    'username' => env('DATABASE_USER'),
    'password' => env('DATABASE_PASS'),
    'charset' => 'utf8',
];
