<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use common\models\Item;
use yii\helpers\ArrayHelper;

class Artist extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%artists}}';
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
        return $this->hasMany(Item::class, ['artist_id' => 'id']);
    }

    public static function getList(): array
    {
        $artists = self::find()->all();
        return ArrayHelper::map($artists, 'id', 'name');
    }
}
