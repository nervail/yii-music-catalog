<?php

namespace common\search;

use common\entities\Artist;
use yii\data\ActiveDataProvider;

class ArtistSearch extends Artist
{
    const PAGE_SIZE = 10;
    const PAGE_SIZE_LIMIT = [1, 50];

    public function rules()
    {
        return [
            [['name'], 'trim'],
            [['name'], 'string'],
            
            [['id'], 'integer'],
        ];
    }

    public function search($params, $formName = null): ActiveDataProvider
    {
        $query = Artist::find()->with('albums', 'items');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'defaultPageSize' => self::PAGE_SIZE, 
                'pageSizeLimit' => self::PAGE_SIZE_LIMIT, 
                'pageSizeParam' => 'per-page',
            ],
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

        $query->andFilterWhere(['id' => $this->id]);
        $query->andFilterWhere(['like', 'name', $this->name]);
        return $dataProvider;
    }
}
