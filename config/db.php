<?php

return [
    'class' => 'yii\db\Connection',
    'dsn' => env('DB_CONNECTION', 'mysql') . ':host=' . env('DB_HOST', 'localhost') . ';' . 'dbname=' . env('DB_DATABASE', 'yii2basic'),
    'username' => env('DB_USERNAME', 'root'),
    'password' => env('DB_PASSWORD', ''),
    'charset' => env('DB_CHARSET', 'utf8'),
];
