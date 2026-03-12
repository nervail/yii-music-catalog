<?php

namespace common\fixtures;

use yii\test\ActiveFixture;

class GenreFixture extends ActiveFixture
{
    public $tableName = '{{%genres}}';
    public $dataFile = __DIR__ . '/data/genres.php';
}