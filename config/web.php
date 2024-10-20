<?php

$config = [
    'id' => env('APP_ID', 'basic'),
    'name' => env('APP_NAME', 'My Yii Application'),
    'defaultRoute' => '/dashboard/index',
    'language' => 'ru-RU',
    'modules' => [
        'user' => [
            'class' => 'dektrium\user\Module',
            'adminPermission' => 'admin',
            'modelMap' => [
                'User' => 'app\models\Users',
                'UserSearch' => 'app\models\UsersSearch',
                'Profile' => 'app\models\Profile',
                'RegistrationForm' => 'app\models\forms\RegistrationForm',
                'LoginForm' => 'app\models\forms\LoginForm',
            ],
            'controllerMap' => [
                'security' => [
                    'class' => 'dektrium\user\controllers\SecurityController',
                    'layout' => '@app/views/layouts/public',
                ],
                'registration' => [
                    'class' => 'dektrium\user\controllers\RegistrationController',
                    'layout' => '@app/views/layouts/public',
                ],
                'recovery' => [
                    'class' => 'dektrium\user\controllers\RecoveryController',
                    'layout' => '@app/views/layouts/public',
                ],
                'admin' => 'app\controllers\user\AdminController',
                'settings' => 'app\controllers\user\SettingsController',
            ],
            'mailer' => [
                'class' => 'app\components\Mailer',

            ]
    	],
        'rbac' => 'dektrium\rbac\RbacWebModule',
        'gridview' =>  [
            'class' => 'kartik\grid\Module'
            // enter optional module parameters below - only if you need to
            // use your own export download action or custom translation
            // message source
            // 'downloadAction' => 'gridview/export/download',
            // 'i18n' => []
        ],
	],
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => env('COOKIE_VALIDATION_KEY', 'dVMBF16b92VgBttoz3-qnG-wEztA1haA'),
            'enableCsrfValidation' => true,
        ],
        'errorHandler' => [
            'errorAction' => 'dashboard/error',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
        ],
        'assetManager' => [
            'appendTimestamp' => true,
            'bundles' => [
                'yii\jui\JuiAsset' => [
                    'css' => [
                        'themes/base/jquery-ui.css',
                    ]
                ],
            ],
        ],
        'view' => [
            'theme' => [
                'pathMap' => [
                    '@dektrium/user/views' => '@app/views/dektrium'
                ],
            ],
        ],
    ],
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
    ];
}

return $config;
