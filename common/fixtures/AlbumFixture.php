<?php

namespace common\fixtures;

use yii\test\ActiveFixture;

class AlbumFixture extends ActiveFixture
{
    public $tableName = '{{%albums}}';
    public $dataFile = __DIR__ . '/data/albums.php';
}