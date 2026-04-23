<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use common\models\Item;
use yii\helpers\ArrayHelper;

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
        return $this->hasMany(Item::class, ['id' => 'item_id'])
                    ->viaTable('{{%item_genres}}', ['genre_id' => 'id']);
    }

    public static function getList(): array
    {
        $genres = self::find()->all();
        return ArrayHelper::map($genres, 'id', 'name');
    }
}
