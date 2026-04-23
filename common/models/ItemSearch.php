<?php

namespace common\models;

use common\models\Item;
use yii\data\ActiveDataProvider;

class ItemSearch extends Item
{
    public $genre_ids = [];

    public function rules()
    {
        return [
            [['name', 'description'], 'string'],
            [['id', 'artist_id', 'status'], 'integer'],
            ['genre_ids', 'each', 'rule' => ['integer']],
        ];
    }

    public function search($params)
    {
        $query = Item::find()->with(['artist', 'genres']);
        $query->joinWith('genres', false);
        
        $query->distinct();

        $dataProvider = new ActiveDataProvider([
            'pagination' => [
                'pageSize' => 10
            ],
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere(['items.id' => $this->id])
            ->andFilterWhere(['like', 'items.name', $this->name])
            ->andFilterWhere(['like', 'items.description', $this->description])
            ->andFilterWhere(['items.status' => $this->status])
            ->andFilterWhere(['items.artist_id' => $this->artist_id])
            ->andFilterWhere(['genres.id' => $this->genre_ids]);

        return $dataProvider;
    }
}