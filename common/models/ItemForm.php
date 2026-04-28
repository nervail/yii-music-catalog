<?php

namespace common\models;

use yii\base\Model;
use yii\helpers\ArrayHelper;

class ItemForm extends Model
{
    public const SCENARIO_CREATE = 'create';
    public const SCENARIO_UPDATE = 'update';

    public $id;
    public $name;
    public $description;
    public $image;
    public $artist_id;
    public $genre_ids = [];
    public $currentImage;
    private ?Item $_item = null;

    public function rules()
    {
        return [
            [['name', 'description', 'artist_id', 'genre_ids'], 'required'],
            [['artist_id'], 'integer'],
            [['genre_ids'], 'each', 'rule' => ['integer']],
            [['genre_ids'], 'each', 'rule' => [
                'exist', 
                'targetClass' => Genre::class, 
                'targetAttribute' => 'id'
            ]],
            [['name'], 'string', 'max' => 255],
            [['description'], 'string'],
            [['image'], 'file', 
                'skipOnEmpty' => true, 
                'extensions' => 'png, jpg, jpeg',
                'mimeTypes' => 'image/jpeg, image/png',
                'maxSize' => 5*1024*1024
            ],
            [['image'], 'required', 'on' => self::SCENARIO_CREATE],
        ];
    }

    public function scenarios()
    {
        return [
            self::SCENARIO_CREATE => ['name', 'description', 'artist_id', 'genre_ids', 'image'],
            self::SCENARIO_UPDATE => ['name', 'description', 'artist_id', 'genre_ids', 'image'],
        ];
    }

    public function setFromModel(Item $item): void
    {
        $this->_item = $item;

        $this->setAttributes($item->getAttributes());
        $this->genre_ids = ArrayHelper::getColumn($item->genres, 'id');
        $this->currentImage = $item->getImageLink();
    }

    public function save(): bool
    {
        if ($this->scenario === self::SCENARIO_UPDATE && !$this->_item) {
            throw new \yii\base\InvalidCallException('Для сценария update необходимо вызвать setFromModel()');
        }

        if (!$this->validate()) {
            return false;
        }

        $uploadedKey = null;

        $transaction = \Yii::$app->db->beginTransaction();

        try {
            $item = $this->_item ?? new Item();

            $oldImageKey = $item->image_url;

            if ($this->image) {
                $uploadedKey = \Yii::$app->storage->uploadFile(
                    $this->image->tempName,
                    $this->image->extension,
                    $this->image->type
                );

                $item->image_url = $uploadedKey;
            }

            $item->name = $this->name;
            $item->description = $this->description;
            $item->artist_id = $this->artist_id;

            if (!$item->save()) {
                $firstError = current($item->getFirstErrors());
                $transaction->rollBack();
                throw new \Exception($firstError ?: 'Ошибка сохранения в БД');
            }

            // Тут логика сохранения жанров
            $item->unlinkAll('genres', true);

            $genres = Genre::findAll($this->genre_ids);

            foreach ($genres as $genre) {
                $item->link('genres', $genre);
            }

            $transaction->commit();

            if (($uploadedKey && $oldImageKey) && ($uploadedKey !== $oldImageKey)) {
                \Yii::$app->storage->delete($oldImageKey);
            }

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