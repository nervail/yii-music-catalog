<?php

namespace common\models;

use yii\base\Model;
use yii\helpers\ArrayHelper;

class AlbumForm extends Model
{
    public const SCENARIO_CREATE = 'create';
    public const SCENARIO_UPDATE = 'update';
    public const SCENARIO_GET_ALBUMS = 'get-albums';

    public $id;
    public $name;
    public $artist_id;
    public $image;
    public $release_date;
    public $currentImage;
    private ?Album $_album = null;

    public function rules()
    {
        return [
            // Общие правила валидации для всех сценариев
            [['artist_id'], 'integer'],
            [['name'], 'string', 'max' => 255],
            [['release_date'], 'date', 'format' => 'php:Y-m-d'],
            [['artist_id'], 'exist', 'skipOnError' => true, 'targetClass' => Artist::class, 'targetAttribute' => 'id'],
            [['image'], 'file', 
                'skipOnEmpty' => true, 
                'extensions' => 'png, jpg, jpeg',
                'mimeTypes' => 'image/jpeg, image/png',
                'maxSize' => 10*1024*1024
            ],
            
            // Create
            [['name', 'release_date', 'artist_id', 'image'], 'required', 'on' => self::SCENARIO_CREATE],

            // Update
            [['name', 'release_date', 'artist_id'], 'required', 'on' => self::SCENARIO_UPDATE],

            // Get Albums
            [['artist_id'], 'required', 'on' => self::SCENARIO_GET_ALBUMS],
            [['name'], 'safe', 'on' => self::SCENARIO_GET_ALBUMS],
        ];
    }

    public function scenarios()
    {
        return [
            self::SCENARIO_CREATE => ['name', 'release_date', 'artist_id', 'image'],
            self::SCENARIO_UPDATE => ['name', 'release_date', 'artist_id', 'image'],
            self::SCENARIO_GET_ALBUMS => ['name', 'artist_id'],
        ];
    }

    public function setFromModel(Album $album): void
    {
        $this->_album = $album;

        $this->setAttributes($album->getAttributes());
        $this->currentImage = $album->getImageLink();
    }

    public function save(): bool
    {
        if ($this->scenario === self::SCENARIO_UPDATE && !$this->_album) {
            throw new \yii\base\InvalidCallException('Для сценария update необходимо вызвать setFromModel()');
        }
    
        if (!$this->validate()) {
            return false;
        }

        $uploadedKey = null;

        $transaction = \Yii::$app->db->beginTransaction();

        try {
            $album = $this->_album ?? new Album();

            $oldImageKey = $album->image_url;

            if ($this->image) {
                $uploadedKey = \Yii::$app->storage->uploadFile(
                    $this->image->tempName,
                    $this->image->extension,
                    $this->image->type,
                    'albums'
                );

                $album->image_url = $uploadedKey;
            }

            $album->name = $this->name;
            $album->release_date = $this->release_date;
            $album->artist_id = $this->artist_id;

            if (!$album->save()) {
                $firstError = current($album->getFirstErrors());
                $transaction->rollBack();
                throw new \Exception($firstError ?: 'Ошибка сохранения в БД');
            }

            $transaction->commit();

            if (($uploadedKey && $oldImageKey) && ($uploadedKey !== $oldImageKey)) {
                \Yii::$app->storage->delete($oldImageKey);
            }
            
            $this->id = $album->id;
            
            return true;
        } catch (\Throwable $e) {
            if ($uploadedKey) {
                \Yii::$app->storage->delete($uploadedKey);
            }
            
            $transaction->rollBack();

            \Yii::error($e);
            \Yii::$app->session->setFlash('error', 'Произошла техническая ошибка при сохранении. Пожалуйста, попробуйте позже');
            return false;
        }
    }
}