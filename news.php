<?php
/**
 * Yii console bootstrap file.
 *
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

declare(ticks = 1);

defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'dev');

require(__DIR__ . '/vendor/autoload.php');
require(__DIR__ . '/vendor/yiisoft/yii2/Yii.php');

require(__DIR__ . '/service/Application.php');

$config = require(__DIR__ . '/config/news.php');

$application = new app\service\Application($config);

if(php_sapi_name() == 'cli') {
    $application->register();
    pcntl_signal(SIGTERM, function($signo) use ($application){
        $application->deregister();
        echo 'Successfully deregister service' . PHP_EOL;
    });

    pcntl_signal(SIGINT, function($signo) use ($application){
        $application->deregister();
        echo 'Successfully deregister service' . PHP_EOL;
    });
    if(!isset($application->service['port'])){
        throw new \yii\base\InvalidConfigException('Port must be set.');
    }
    echo shell_exec('php -S ' . ($application->service['ip'] ?? '127.0.0.1'). ':' . ($application->service['port'] ?? '8888') . ' ' . __FILE__);
} else {
    $exitCode = $application->run();
    exit($exitCode);
}
