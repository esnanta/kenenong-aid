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
    'bootstrap' => ['log', InertiaBootstrap::class],
    'aliases' => [
        '@bower' => '@vendor/yidas/yii2-bower-asset/bower',
        '@npm'   => '@vendor/npm-asset',
    ],
    'modules' => [
        'user' => [
            'class' => 'Da\User\Module',
            'administrators' => ['admin'],
            'enableRegistration' => true,
            'enableEmailConfirmation' => true,
            // Override default controllers with our custom ones
            'controllerMap' => [
                'admin' => 'app\controllers\UserController',
                'role' => 'app\controllers\RoleController',
                'permission' => 'app\controllers\PermissionController',
                'rule' => 'app\controllers\RuleController',
                'security' => 'app\controllers\SecurityController',
                'registration' => 'app\controllers\RegistrationController',
                'recovery' => 'app\controllers\RecoveryController',
            ],
        ],
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
            //'class' => 'yii\redis\Cache',
        ],
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
            'itemTable' => 't_auth_item',
            'itemChildTable' => 't_auth_item_child',
            'assignmentTable' => 't_auth_assignment',
            'ruleTable' => 't_auth_rule',
        ],
        'user' => [
            'identityClass' => app\models\User::class, // extend to Da\User\Model\User
            'enableAutoLogin' => true,
            'loginUrl' => ['/login'], // Custom login route
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
                    'logVars' => ['_GET', '_POST', '_FILES', '_COOKIE', '_SESSION', '_SERVER'],
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

                // Authentication routes (menggunakan module 'user')
                'login' => 'user/security/login',
                'logout' => 'user/security/logout',
                'register' => 'user/registration/register',
                'confirm/<id:\d+>/<code:[A-Za-z0-9_-]+>' => 'user/registration/confirm',
                'resend' => 'user/registration/resend',
                'forgot-password' => 'user/recovery/request',
                'reset-password/<id:\d+>/<code:[A-Za-z0-9_-]+>' => 'user/recovery/reset',

                // Dashboard routes
                'dashboard' => 'dashboard/index',
                'dashboard/<action:\w+>' => 'dashboard/<action>',

                // User management routes (CRUD) - gunakan 'admin' sebagai controller name
                'users' => 'user/admin/index',
                'users/create' => 'user/admin/create',
                'users/<id:\d+>' => 'user/admin/view',
                'users/<id:\d+>/update' => 'user/admin/update',
                'users/<id:\d+>/delete' => 'user/admin/delete',

                // Role management
                'roles' => 'role/index',
                'roles/create' => 'role/create',
                'roles/<name:.+>/update' => 'role/update',
                'roles/<name:.+>/delete' => 'role/delete',
                'roles/<name:.+>' => 'role/view',

                // Permission management
                'permissions' => 'permission/index',
                'permissions/create' => 'permission/create',
                'permissions/<name:.+>/update' => 'permission/update',
                'permissions/<name:.+>/delete' => 'permission/delete',
                'permissions/<name:.+>' => 'permission/view',

                // Rule management
                'rules' => 'rule/index',
                'rules/create' => 'rule/create',
                'rules/<name:.+>/update' => 'rule/update',
                'rules/<name:.+>/delete' => 'rule/delete',
                'rules/<name:.+>' => 'rule/view',

                // Disaster routes
                'disasters' => 'disaster/index',
                'disasters/create' => 'disaster/create',
                'disasters/<id:\d+>' => 'disaster/view',
                'disasters/<id:\d+>/update' => 'disaster/update',
                'disasters/<id:\d+>/delete' => 'disaster/delete',

                // Disaster Status routes
                'disaster-statuses' => 'disaster-status/index',
                'disaster-statuses/create' => 'disaster-status/create',
                'disaster-statuses/<id:\d+>/update' => 'disaster-status/update',
                'disaster-statuses/<id:\d+>/delete' => 'disaster-status/delete',
                'disaster-statuses/<id:\d+>' => 'disaster-status/view',

                // Disaster Type routes
                'disaster-types' => 'disaster-type/index',
                'disaster-types/create' => 'disaster-type/create',
                'disaster-types/<id:\d+>/update' => 'disaster-type/update',
                'disaster-types/<id:\d+>/delete' => 'disaster-type/delete',
                'disaster-types/<id:\d+>' => 'disaster-type/view',

                // Generic routes (fallback)
                '<controller:\w+>/<id:\d+>' => '<controller>/view',
                '<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
                '<controller:\w+>/<action:\w+>' => '<controller>/<action>',
            ],
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
