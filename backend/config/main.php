<?php

use yii\web\JsonParser;
use yii\web\UrlNormalizer;

$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-backend',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers',
    'bootstrap' => [
        'log',
    ],
    'modules' => [
        'admin' => [
            'class' => 'backend\modules\admin\Module',
        ],
    ],
    'container' => [
        'definitions' => [
            \yii\widgets\LinkPager::class => \yii\bootstrap5\LinkPager::class,
        ],
    ],
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-backend',
            'parsers' => [
                'application/json' => JsonParser::class,
            ],
        ],
        'user' => [
            'identityClass' => 'common\entities\User',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-backend', 'httpOnly' => true],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the backend
            'name' => 'advanced-backend',
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
            'errorAction' => 'admin/site/error',
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
                '' => 'admin/site/index',
            ],
        ],
        'formatter' => [
            'class' => yii\i18n\Formatter::class,
            'datetimeFormat' => 'php:d.m.Y H:i',
            'dateFormat' => 'php:d.m.Y',
            'timeFormat' => 'php:H:i',
            'locale' => 'ru-RU',
        ],
    ],
    'params' => $params,
];
