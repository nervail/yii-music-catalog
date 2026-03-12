<?php

namespace common\fixtures;

use yii\test\ActiveFixture;

class UserFixture extends ActiveFixture
{
    public $tableName = '{{%users}}';
    public $dataFile = __DIR__ . '/data/users.php';
}