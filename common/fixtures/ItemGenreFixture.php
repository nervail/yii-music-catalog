<?php

namespace common\fixtures;

use yii\test\ActiveFixture;

class ItemGenreFixture extends ActiveFixture
{
    public $tableName = '{{%item_genres}}';
    public $dataFile = __DIR__ . '/data/item_genres.php';

    public $depends = [
        'common\fixtures\ItemFixture',
        'common\fixtures\GenreFixture',
    ];
}