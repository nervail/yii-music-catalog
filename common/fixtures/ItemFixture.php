<?php

namespace common\fixtures;

use yii\test\ActiveFixture;

class ItemFixture extends ActiveFixture
{
    public $tableName = '{{%items}}';
    public $dataFile = __DIR__ . '/data/items.php';
}