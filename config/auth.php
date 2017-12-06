<?php

$params = require(__DIR__ . '/params.php');

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'consul' => 'http://localhost:8500',
    'service' => [
        'port' => '8087',
        'id' => 'auth',
        'name' => 'auth',
        'tags' => ['auth'],
        'salt' => sha1('some salt')
    ],
    'bootstrap' => ['log'],
    'components' => [
        'request' => [
            'cookieValidationKey' => 'asdhakdhafhas541321321',
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
            ]
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
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
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=127.0.0.1;port=3311;dbname=auth;',
            'username' => 'root',
            'password' => '204655',
            'charset' => 'utf8',
        ],
        'db_auth' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=127.0.0.1;port=3311;dbname=auth;',
            'username' => 'root',
            'password' => '204655',
            'charset' => 'utf8',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'enableStrictParsing' => true,
            'showScriptName' => false,
            'rules' => [
                [
                    'class' => 'yii\rest\UrlRule', 'pluralize' => false, 'controller' => 'auth',
                    'extraPatterns' => [
                        'POST' => 'sign-up',
                        'POST login' => 'login',
                        'POST logout' => 'logout',
                        'PUT user' => 'user'
                    ]
                ],
                '/health/check' => 'health/check'
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
