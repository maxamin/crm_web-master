<?php

Yii::setAlias('@tests', dirname(__DIR__) . '/tests/codeception');

$config = [
    'id' => env('APP_CONSOLE_ID', 'basic-console'),
    'controllerNamespace' => 'app\commands',
    'modules' => [
        'dektrium-rbac' => 'dektrium\rbac\RbacConsoleModule',
    ],
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
    ];
}

return $config;
