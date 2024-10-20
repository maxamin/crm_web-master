<?php

$config = [
    'basePath' => dirname(__DIR__),
    'bootstrap' => [
        'log',
        'maintenanceMode'
    ],
    'timeZone' => 'Europe/Kiev',
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'log' => [
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => '192.168.59.22',
                'username' => 'crm@concord.ua',
                'password' => 'Qw123456',
                'port' => '25',
                'encryption' => false,
            ],
        ],
        'db' => require(__DIR__ . '/db.php'),
        'authManager' => [
            'class' => 'app\framework\rbac\DbManager',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'enableStrictParsing' => false,
            'rules' => [

                'dashboard' => 'dashboard/index',

                'contacts/<type:\d+>' => 'contact/index',
                'contact/unlink/<id:\d+>' => 'contact/unlink',
                'contact/create/<type:\d+>' => 'contact/create',

                '<controller:\w+>s' => '<controller>/index',
                '<controller:\w+>/create' => '<controller>/create',
                '<controller:\w+>/<action:view|update|delete>/<id:\d+>' => '<controller>/<action>',
            ],
        ],
        'formatter' => [
            'class' => 'yii\i18n\Formatter',
            'dateFormat' => 'php:d.m.Y',
            'datetimeFormat' => 'php:d.m.Y H:i:s',
            'timeFormat' => 'php:H:i:s',
            'defaultTimeZone' => 'Europe/Kiev',
            'nullDisplay' => '',
        ],
        'maintenanceMode' => [
            'class' => 'brussens\maintenance\MaintenanceMode',
            'title' => 'Скоро, наверное, все заработает... =)',
            'message' => 'Извините, проводятся технические работы.',
            'roles' => ['admin'],

            'urls' => [
                'user/security/login'
            ],

            // Mode status
            'enabled' => false,
        ],
    ],
    'aliases' => require(__DIR__ . '/aliases.php'),
    'params' => require(__DIR__ . '/params.php'),
];

return $config;