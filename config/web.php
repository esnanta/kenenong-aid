<?php

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log', \app\components\InertiaBootstrap::class],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'components' => [
         'view' => [
            'renderers' => [
                'inertia' => \Crenspire\Yii2Inertia\ViewRenderer::class,
            ],
        ],
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => '7lNroFDaY_F0Sa-UHP2O_2uwMTPiLk8v',
            // Enable JSON parser for Inertia requests
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
            ],
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
        ],
        'errorHandler' => [
            'class' => \app\components\InertiaErrorHandler::class,
            'errorAction' => 'home/error',
        ],
        'mailer' => [
            'class' => \yii\symfonymailer\Mailer::class,
            'viewPath' => '@app/mail',
            // send all mails to a file by default.
            'useFileTransport' => true,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db' => $db,
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'enableStrictParsing' => false,
            'rules' => [
                '' => 'home/index',
                'login' => 'auth/login',
                'register' => 'auth/register',
                'logout' => 'auth/logout',
                'forgot-password' => 'auth/forgot-password',
                'reset-password' => 'auth/reset-password',
                'dashboard' => 'dashboard/index',
                'dashboard/<action:\w+>' => 'dashboard/<action>',
                'users' => 'user/index',
                'users/create' => 'user/create',
                'users/<id:\d+>' => 'user/view',
                'users/<id:\d+>/edit' => 'user/update',
                'users/<id:\d+>/delete' => 'user/delete',
                'disasters' => 'disaster/index',
                'disasters/create' => 'disaster/create',
                'disasters/<id:\d+>' => 'disaster/view',
                'disasters/<id:\d+>/edit' => 'disaster/update',
                'disasters/<id:\d+>/delete' => 'disaster/delete',
                '<controller:\w+>/<id:\d+>' => '<controller>/view',
                '<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
                '<controller:\w+>/<action:\w+>' => '<controller>/<action>',
            ],
        ],
        /*
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
            ],
        ],
        */
    ],
    'params' => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];
}

return $config;
