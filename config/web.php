<?php

use app\components\InertiaBootstrap;
use app\components\InertiaErrorHandler;
use Crenspire\Yii2Inertia\ViewRenderer;
use yii\symfonymailer\Mailer;

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log', InertiaBootstrap::class, 'user'],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'components' => [
         'view' => [
            'renderers' => [
                'inertia' => ViewRenderer::class,
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
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
        ],
        'user' => [
            'identityClass' => app\models\User::class, // extend to Da\User\Model\User
            'enableAutoLogin' => true,
            'loginUrl' => ['/user/security/login'], // usuario's login route
        ],
        'errorHandler' => [
            'class' => InertiaErrorHandler::class,
            'errorAction' => 'home/error',
        ],
        'mailer' => [
            'class' => Mailer::class,
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
                'user' => 'user/index',
                'user/create' => 'user/create',
                'user/<id:\d+>' => 'user/view',
                'user/<id:\d+>/edit' => 'user/update',
                'user/<id:\d+>/delete' => 'user/delete',
                'disaster' => 'disaster/index',
                'disaster/create' => 'disaster/create',
                'disaster/<id:\d+>' => 'disaster/view',
                'disaster/<id:\d+>/edit' => 'disaster/update',
                'disaster/<id:\d+>/delete' => 'disaster/delete',
                '<controller:\w+>/<id:\d+>' => '<controller>/view',
                '<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
                '<controller:\w+>/<action:\w+>' => '<controller>/<action>',
            ],
        ],

    ],

    'modules' => [
        'user' => [
            'class' => 'Da\User\Module',
            'administrators' => ['admin'],
            'enableRegistration' => true,
            'enableEmailConfirmation' => true,
        ],
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
