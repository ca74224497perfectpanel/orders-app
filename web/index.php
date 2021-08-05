<?php

use yii\helpers\ArrayHelper;

defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'dev');

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../vendor/yiisoft/yii2/Yii.php';

$localConfig = (file_exists(__DIR__ . '/../config/web-local.php')) ?
    require(__DIR__ . '/../config/web-local.php') : [];

$config = ArrayHelper::merge(
    require(__DIR__ . '/../config/web.php'),
    $localConfig
);

(new yii\web\Application($config))->run();
