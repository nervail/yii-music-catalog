<?php

namespace common\entities;

use yii\db\ActiveRecord;
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

    public function fields()
    {
        return [
            'id',
            'name',
        ];
    }

    public function extraFields()
    {
        return ['albums'];
    }

    public static function getList(): array
    {
        $artists = self::find()->all();
        return ArrayHelper::map($artists, 'id', 'name');
    }

    public function getAlbums()
    {
        return $this->hasMany(Album::class, ['artist_id' =>'id']);
    }

    public function getSubscriptions()
    {
        return $this->hasMany(Subscription::class, ['artist_id' => 'id']);
    }
}
