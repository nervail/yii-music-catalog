<?php

namespace common\entities;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use common\entities\Genre;
use common\entities\Album;

class Item extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%items}}';
    }

    public function behaviors()
    {
        return [
            TimestampBehavior::class,
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'description', 'image_url'], 'required'],
            [['name', 'description'], 'string'],

            [['album_id'], 'required'],
            [['album_id'], 'integer'],
            [['album_id'], 'exist',
                'targetClass' => Album::class,
                'targetAttribute' => 'id',
            ],
        ];
    }

    public function fields()
    {
        return [
            'id',
            'name',
            'description',
            'image_url' => function ($model) {
                return $model->getImageLink();
            },
            'album_id',
        ];
    }

    public function extraFields()
    {
        return ['album', 'genres'];
    }

    public function getGenres()
    {
        return $this->hasMany(Genre::class, ['id' => 'genre_id'])
                    ->viaTable('{{%item_genres}}', ['item_id' => 'id']);
    }

    public function getAlbum()
    {
        return $this->hasOne(Album::class, ['id' => 'album_id']);
    }

    public function getImageLink()
    {
        return Yii::$app->storage->getUrl($this->image_url);
    }

    public function beforeDelete()
    {
        if (!parent::beforeDelete()) {
            return false;
        }

        $this->unlinkAll('genres', true);

        if ($this->image_url) {
            \Yii::$app->storage->delete($this->image_url);
        }

        return true;
    }
}