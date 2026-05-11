<?php

namespace common\entities;

use Yii;
use yii\behaviors\TimestampBehavior;
use common\entities\Artist;
use common\entities\Item;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "albums".
 *
 * @property int $id
 * @property string $name
 * @property int $artist_id
 * @property string|null $release_date
 * @property string|null $image_url
 * @property int $created_at
 * @property int $updated_at
 *
 * @property Artist $artist
 * @property Item[] $items
 */
class Album extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%albums}}';
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
            [['release_date', 'image_url'], 'default', 'value' => null],
            [['name', 'artist_id'], 'required'],
            [['artist_id'], 'integer'],
            [['release_date'], 'safe'],
            [['name'], 'string', 'max' => 255],
            [['artist_id'], 'exist', 'skipOnError' => true, 'targetClass' => Artist::class, 'targetAttribute' => ['artist_id' => 'id']],
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
        return ['artist', 'items'];
    }

    /**
     * Gets query for [[Artist]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getArtist()
    {
        return $this->hasOne(Artist::class, ['id' => 'artist_id']);
    }

    /**
     * Gets query for [[Items]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getItems()
    {
        return $this->hasMany(Item::class, ['album_id' => 'id']);
    }

    public function getImageLink()
    {
        return Yii::$app->storage->getUrl($this->image_url);
    }

    public static function getListByArtist(int $artistId)
    {
        return ArrayHelper::map(self::findAll(['artist_id' => $artistId]), 'id', 'name');
    }

}
