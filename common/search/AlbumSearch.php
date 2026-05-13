<?php

namespace common\search;

use yii\data\ActiveDataProvider;
use common\entities\Album;

/**
 * AlbumSearch represents the model behind the search form of `common\entities\Album`.
 */
class AlbumSearch extends Album
{
    const PAGE_SIZE = 10;
    const PAGE_SIZE_LIMIT = [1, 50];

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'trim'],
            [['name'], 'string'],

            [['id', 'artist_id'], 'integer'],
            [['release_date'], 'safe'],
        ];
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     * @param string|null $formName Form name to be used into `->load()` method.
     *
     * @return ActiveDataProvider
     */
    public function search($params, $formName = null): ActiveDataProvider
    {
        $query = Album::find()->with('artist', 'items');

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => ['id' => SORT_DESC],
            ],
            'pagination' => [
                'defaultPageSize' => self::PAGE_SIZE, 
                'pageSizeLimit' => self::PAGE_SIZE_LIMIT, 
                'pageSizeParam' => 'per-page',
            ],
        ]);

        if (!$this->load($params, $formName)) {
            return $dataProvider;
        }

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'artist_id' => $this->artist_id,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name]);

        return $dataProvider;
    }
}
