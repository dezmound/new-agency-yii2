<?php

$params = require(__DIR__ . '/params.php');

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'consul' => getenv('CONSUL_URI') ?: 'http://localhost:8500',
    'service' => [
        'port' => 80,
        'id' => 'reporters',
        'ip' => getenv('HOST') ?: '0.0.0.0',
        'name' => 'reporters',
        'tags' => ['reporters']
    ],
    'components' => [
        'request' => [
            'cookieValidationKey' => 'asdhakdhafhas5413213dasd21',
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
            ]
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
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
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
        ],
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=reporters;port=3306;dbname=reporter;',
            'username' => 'root',
            'password' => '204655',
            'charset' => 'utf8',
        ],
        'db_reporters' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=reporters;port=3306;dbname=reporter;',
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
                    'class' => 'yii\rest\UrlRule', 'pluralize' => false, 'controller' => 'reporters',
                    'extraPatterns' => [
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
        'allowedIPs' => ['127.0.0.1', '::1', '172.*'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        'allowedIPs' => ['127.0.0.1', '::1', '172.*'],
    ];
}

return $config;
