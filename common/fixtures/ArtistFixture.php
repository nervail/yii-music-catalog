<?php

namespace common\fixtures;

use yii\test\ActiveFixture;

class ArtistFixture extends ActiveFixture
{
    public $tableName = '{{%artists}}';
    public $dataFile = __DIR__ . '/data/artists.php';
}