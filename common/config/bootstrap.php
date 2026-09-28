<?php

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(dirname(__DIR__, 2));
$dotenv->safeLoad();

\Yii::setAlias('@common', dirname(__DIR__));
\Yii::setAlias('@frontend', dirname(dirname(__DIR__)) . '/frontend');
\Yii::setAlias('@backend', dirname(dirname(__DIR__)) . '/backend');
\Yii::setAlias('@console', dirname(dirname(__DIR__)) . '/console');
