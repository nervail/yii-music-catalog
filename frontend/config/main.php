<?php

use frontend\components\api\ApiErrorHandler;
use yii\rest\UrlRule;
use yii\web\JsonParser;
use yii\web\Response;
use yii\web\UrlNormalizer;

$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-frontend',
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
            'class' => ApiErrorHandler::class,
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
                'POST api/signup' => 'api/site/signup',
                'POST api/logout' => 'api/site/logout',

                'GET api' => 'api/site/index',

                'GET api/items' => 'api/item/index',
                'GET api/items/<id:\d+>' => 'api/item/view',
                
                'GET api/genres' => 'api/genre/index',
                'GET api/genres/<id:\d+>' => 'api/genre/view',

                'GET api/albums' => 'api/album/index',
                'GET api/albums/<id:\d+>' => 'api/album/view',

                'GET api/artists' => 'api/artist/index',
                'GET api/artists/<id:\d+>' => 'api/artist/view',
            ],
        ],
    ],
    'params' => $params,
];
