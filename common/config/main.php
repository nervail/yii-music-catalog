<?php

use common\repositories\UserRepository;

return [
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'container' => [
        'singletons' => [
            UserRepository::class => UserRepository::class,
        ],
    ],
    'components' => [
        'cache' => [
            'class' => \yii\caching\FileCache::class,
        ],

        'authManager' => [
            'class' => 'yii\rbac\DbManager',
        ],

        'queue' => [
            'class' => \yii\queue\db\Queue::class,
            'db' => 'db',
            'tableName' => '{{%queue}}',
            'channel' => 'default',
            'mutex' => \yii\mutex\MysqlMutex::class,
        ],

        'db' => [
            'class' => \yii\db\Connection::class,
            'dsn' => 'mysql:host=' . $_ENV['DB_HOST'] . ';dbname=' . $_ENV['DB_NAME'],
            'username' => $_ENV['DB_USER'],
            'password' => $_ENV['DB_PASSWORD'],
            'charset' => 'utf8mb4',
        ],

        'storage' => [
            'class' => 'common\services\StorageService',
        ],

        'mailer' => [
            'class' => \yii\symfonymailer\Mailer::class,
            'viewPath' => '@common/mail',
            'useFileTransport' => false,

            'transport' => [
                'scheme' => 'smtp',
                'host' => $_ENV['MAILER_HOST'],
                'port' => (int) $_ENV['MAILER_PORT'],
                'encryption' => $_ENV['MAILER_ENCRYPTION'] ?: null,
                'username' => $_ENV['MAILER_USERNAME'],
                'password' => $_ENV['MAILER_PASSWORD'],
            ],
            
            'messageConfig' => [
                'from' => [
                    $_ENV['MAILER_FROM_EMAIL'] => $_ENV['MAILER_FROM_NAME']
                ],
            ],
        ],
    ],
];
