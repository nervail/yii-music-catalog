<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use common\models\Item;

class Genre extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%genres}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['name', 'required'],
            ['name', 'string'],
        ];
    }

    public function getItems()
    {
        return $this->hasMany(Item::class, ['genre_id' => 'id']);
    }
}
