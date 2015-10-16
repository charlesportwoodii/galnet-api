<?php

$config = [
    'id' => 'galnet-api',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'components' => [
        'request' => [
            'enableCookieValidation'    => false,
            'enableCsrfValidation'      => false,
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
            ]
        ],
		'response' => [
			'format'         => yii\web\Response::FORMAT_JSON,
			'charset'        => 'UTF-8',
            'on beforeSend'  => ['app\components\ResponseEvent', 'beforeSend']
		],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
		'urlManager' => [
            'class'                 => 'yii\web\UrlManager',
            'showScriptName'        => false,
            'enableStrictParsing'   => false,
            'enablePrettyUrl'       => true,
            'rules' => [
                [
                    'pattern' => '/news',
                    'suffix' => '.rss',
                    'route' => 'news/rss'
                ]
            ]
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
        'db' => require(__DIR__ . '/db.php'),
    ],
    'params' => require(__DIR__ . '/params.php'),
];

return $config;
