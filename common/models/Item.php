<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use common\models\Artist;
use common\models\Genre;

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
            [['name', 'description', 'artist_id', 'image_url'], 'required'],
            [['name', 'description'], 'string'],
            [['artist_id', 'genre_id'], 'integer'],
        ];
    }

    public function getArtist()
    {
        return $this->hasOne(Artist::class, ['id' => 'artist_id']);
    }


    public function getGenres()
    {
        return $this->hasMany(Genre::class, ['id' => 'genre_id'])
                    ->viaTable('{{%item_genres}}', ['item_id' => 'id']);
    }

    public function getImageLink()
    {
        return Yii::$app->storage->getUrl($this->image_url);
    }
}