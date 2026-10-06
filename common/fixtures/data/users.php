<?php

$time = time();

return [
'admin' => [
    'id' => 1,
    'username' => 'admin',
    'auth_key' => Yii::$app->security->generateRandomString(),
    'password_hash' => Yii::$app->security->generatePasswordHash(
        $_ENV['ADMIN_PASSWORD']
    ),
    'password_reset_token' => null,
    'email' => 'admin@example.com',
    'status' => 10,
    'created_at' => $time,
    'updated_at' => $time,
],

'user' => [
    'id' => 2,
    'username' => 'user',
    'auth_key' => Yii::$app->security->generateRandomString(),
    'password_hash' => Yii::$app->security->generatePasswordHash(
        $_ENV['USER_PASSWORD']
    ),
    'password_reset_token' => null,
    'email' => 'user@example.com',
    'status' => 10,
    'created_at' => $time,
    'updated_at' => $time,
],
];
