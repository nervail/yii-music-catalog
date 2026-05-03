<?php

use yii\rest\UrlRule;
use yii\web\JsonParser;
use yii\web\UrlNormalizer;

$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-frontend',
    'as authenticator' => [
        'class' => \yii\filters\auth\HttpBearerAuth::class,
        'optional' => [
            'api/site/login',
            'api/site/index',
            'api/site/signup',
            'api/site/error',
            'debug/*',
            'gii/*',
        ],
    ],
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'frontend\controllers',
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-frontend',
            'parsers' => [
                'application/json' => JsonParser::class
            ],
        ],
        'response' => [
            'format' => \yii\web\Response::FORMAT_JSON,
            'charset' => 'UTF-8',
        ],
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => false,
            'enableSession' => false,
            'loginUrl' => null,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => \yii\log\FileTarget::class,
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'normalizer' => [
                'class' => 'yii\web\UrlNormalizer',
                // use temporary redirection instead of permanent for debugging
                'action' => UrlNormalizer::ACTION_REDIRECT_TEMPORARY,
            ],
            'rules' => [
                'POST api/login' => 'api/site/login',
                'GET api' => 'api/site/index',
                [
                    'class' => UrlRule::class, 
                    'controller' => [
                        'api/item',
                        'api/artist',
                        'api/album',
                    ],
                    'only' => ['index', 'view', 'options']
                ]
            ],
        ],
    ],
    'params' => $params,
];
