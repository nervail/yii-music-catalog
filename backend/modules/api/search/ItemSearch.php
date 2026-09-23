<?php

namespace backend\modules\api\search;

use common\entities\Album;
use common\entities\Item;
use yii\data\ActiveDataProvider;

class ItemSearch extends Item
{
    const PAGE_SIZE = 10;
    const PAGE_SIZE_LIMIT = [1, 50];
    
    public $genre_ids = [];
    public $artist_id;

    public function rules()
    {
        return [
            [['name', 'description'], 'trim'],
            [['name', 'description'], 'string'],
            
            [['id', 'artist_id'], 'integer'],
            ['genre_ids', 'each', 'rule' => ['integer']],
        ];
    }

    public function search($params, $formName = null): ActiveDataProvider
    {
        $query = Item::find()->with(['genres', 'album'])
            ->joinWith('genres', false)
            ->joinWith('album', false)
            ->andWhere([
                'albums.status' => Album::STATUS_PUBLISHED,
            ])
            ->distinct();

        $dataProvider = new ActiveDataProvider([
            'pagination' => [
                'defaultPageSize' => self::PAGE_SIZE, 
                'pageSizeLimit' => self::PAGE_SIZE_LIMIT, 
                'pageSizeParam' => 'per-page',
            ],
            'query' => $query,
            'sort' => [
                'defaultOrder' => ['id' => SORT_DESC],
            ],
        ]);

        if (!$this->load($params, $formName)) {
            return $dataProvider;
        }

        if (!$this->validate()) {
            $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere(['items.id' => $this->id])
            ->andFilterWhere(['like', 'items.name', $this->name])
            ->andFilterWhere(['like', 'items.description', $this->description])
            ->andFilterWhere(['albums.artist_id' => $this->artist_id])
            ->andFilterWhere(['genres.id' => $this->genre_ids]);

        return $dataProvider;
    }
}