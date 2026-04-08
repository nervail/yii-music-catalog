<?php

namespace common\models;

use common\services\StorageService;
use yii\base\Model;
use yii\web\NotFoundHttpException;

class ItemForm extends Model
{
    public $id;
    public $name;
    public $description;
    public $image;
    public $artist_id;
    public $genre_id;
    public $currentImage;
    private ?Item $_item = null;

    private StorageService $storage;

    public function __construct($config = [])
    {
        $this->storage = new StorageService();
        parent::__construct($config);
    }

    public function rules()
    {
        return [
            [['name', 'description', 'artist_id', 'genre_id'], 'required'],
            [['artist_id', 'genre_id'], 'integer'],
            [['name'], 'string', 'max' => 255],
            [['description'], 'string'],
            [['image'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg, jpeg'],
            [['image'], 'required', 'on' => 'create'],
        ];
    }

    public function scenarios()
    {
        return [
            'create' => ['name', 'description', 'artist_id', 'genre_id', 'image'],
            'update' => ['name', 'description', 'artist_id', 'genre_id', 'image'],
        ];
    }

    public function setFromModel(Item $item)
    {
        $this->_item = $item;

        $this->id = $item->id;
        $this->name = $item->name;
        $this->description = $item->description;
        $this->artist_id = $item->artist_id;
        $this->genre_id = $item->genre_id;
        $this->currentImage = $item->image_url;
    }

    public function save(): bool
    {
        if ($this->scenario === 'update' && !$this->_item) {
            throw new \yii\base\InvalidCallException('Для сценария update необходимо вызвать setFromModel()');
        }

        if (!$this->validate()) {
            return false;
        }

        $uploadedKey = null;

        try {
            $item = $this->_item ?? new Item();

            $oldImageKey = $item->image_url;

            if ($this->image) {
                $uploadedKey = $this->storage->uploadFile(
                    $this->image->tempName,
                    $this->image->extension,
                    $this->image->type
                );

                $item->image_url = $uploadedKey;
            }

            $item->name = $this->name;
            $item->description = $this->description;
            $item->artist_id = $this->artist_id;
            $item->genre_id = $this->genre_id;

            if (!$item->save()) {
                throw new \Exception(json_encode($item->errors));
            }

            if ($uploadedKey && $oldImageKey) {
                $this->storage->delete($oldImageKey);
            }

            return true;
        } catch (\Throwable $e) {
            if ($uploadedKey) {
                $this->storage->delete($uploadedKey);
            }

            \Yii::error("Ошибка при сохранении трека: " . $e->getMessage(), 'item_save_error');

            \Yii::$app->session->setFlash('error', 'Произошла техническая ошибка при сохранении. Пожалуйста, попробуйте позже');
            return false;
        }
    }
}