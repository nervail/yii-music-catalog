<?php

namespace common\models;

use common\models\Item;
use yii\data\ActiveDataProvider;

class ItemSearch extends Item
{

    public function rules()
    {
        return [
            [['name', 'description'], 'string'],
            [['id', 'artist_id', 'genre_id', 'status'], 'integer'],
        ];
    }

    public function search($params)
    {
        $query = Item::find()->with(['artist', 'genre']);

        $dataProvider = new ActiveDataProvider([
            'pagination' => [
                'pagesize' => 10
            ],
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere(['id' => $this->id])
            ->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['status' => $this->status])
            ->andFilterWhere(['artist_id' => $this->artist_id])
            ->andFilterWhere(['genre_id' => $this->genre_id]);

        return $dataProvider;
    }
}