<?php

$params = require(__DIR__ . '/params.php');

$config = [
    'id' => 'basic-console',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'app\commands',
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
        'db_news' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=news;port=3306;dbname=new;',
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
        'db_tasks' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=tasks;port=3306;dbname=task;',
            'username' => 'root',
            'password' => '204655',
            'charset' => 'utf8',
        ],
        'db_events' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=events;port=3306;dbname=events;',
            'username' => 'root',
            'password' => '204655',
            'charset' => 'utf8',
        ],
        'db_auth' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=auth;port=3306;dbname=auth;',
            'username' => 'root',
            'password' => '204655',
            'charset' => 'utf8',
        ],
        'db_tokens' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=tokens;port=3306;dbname=token;',
            'username' => 'root',
            'password' => '204655',
            'charset' => 'utf8',
        ],
        'db_users' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=users;port=3306;dbname=user;',
            'username' => 'root',
            'password' => '204655',
            'charset' => 'utf8',
        ],
    ],
    'params' => $params,
    /*
    'controllerMap' => [
        'fixture' => [ // Fixture generation command line.
            'class' => 'yii\faker\FixtureController',
        ],
    ],
    */
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
    ];
}

return $config;
