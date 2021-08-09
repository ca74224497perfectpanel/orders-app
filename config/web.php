<?php

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

$config = [
    'id' => 'Application',
    'basePath' => dirname(__DIR__),
    'defaultRoute' => '/',
    'language' => 'en',
    'bootstrap' => ['log'],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset'
    ],
    'components' => [
        'request' => [
            'cookieValidationKey' => '3ZM6-bt7E55BXohMzmFbjbbN5IrawCIb',
            'baseUrl' => ''
        ],
        'cache' => [
            'class' => 'yii\caching\MemCache',
            'useMemcached' => true
        ],
        'errorHandler' => [
            'errorAction' => 'orders/default/error',
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
            'class' => 'codemix\localeurls\UrlManager',
            'languages' => ['ru', 'en'],
            'enableDefaultLanguageUrlCode' => true,
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                '/' => 'orders/default/index'
            ],
        ],
        'i18n' => [
            'translations' => [
                'text*' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@app/modules/orders/messages',
                ],
            ],
        ],
        'assetManager' => [
            'class' => 'yii\web\AssetManager',
            'bundles' => [
                'yii\web\JqueryAsset' => [
                    'sourcePath' => null,
                    'basePath' => '@webroot',
                    'baseUrl' => '@web',
                    'js' => [
                        'js/jquery.min.js'
                    ]
                ]
            ],
        ],
    ],
    'params' => $params,
    'modules' => [
        'orders' => [
            'class' => 'orders\OrdersModule',
        ],
    ],
];

if (YII_ENV_DEV) {
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        'allowedIPs' => ['*'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        'allowedIPs' => ['*'],
    ];
}

return $config;
