<?php

namespace backend\modules\admin\search;

use yii\data\ActiveDataProvider;
use common\entities\Subscription;

/**
 * SubscriptionSearch represents the model behind the search form of `common\entities\Subscription`.
 */
class SubscriptionSearch extends Subscription
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'user_id', 'artist_id'], 'integer'],
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
        $query = Subscription::find()->with(['user', 'artist']);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
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
            'user_id' => $this->user_id,
            'artist_id' => $this->artist_id,
        ]);

        return $dataProvider;
    }
}
