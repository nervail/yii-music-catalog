<?php

namespace common\entities;

use yii\db\ActiveRecord;
use common\entities\Item;
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

    public function fields()
    {
        return [
            'id',
            'name',
        ];
    }

    public function extraFields()
    {
        return ['items'];
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
