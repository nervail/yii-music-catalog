<?php

namespace common\search;

use common\entities\User;
use yii\data\ActiveDataProvider;

class UserSearch extends User
{
    const PAGE_SIZE = 10;
    const PAGE_SIZE_LIMIT = [1, 50];
    
    public $role;

    public function rules()
    {
        return [
            [['username', 'email'], 'trim'],
            [['username', 'email'], 'string'],

            [['id', 'status'], 'integer'],
            [['role'], 'safe'],
        ];
    }

    public function search($params, $formName = null): ActiveDataProvider
    {
        $query = User::find()->alias('u');

        $query->joinWith('assignment a');

        if (!\Yii::$app->user->can('superAdmin')) {
            $query->andWhere([
                'or', 
                ['not in', 'a.item_name', ['admin', 'superAdmin']],
                ['a.item_name' => NULL]
            ]);
        }

        $query->andWhere(['not', ['id' => \Yii::$app->user->id]]); // Чтобы не видеть себя

        $dataProvider = new ActiveDataProvider([
            'pagination' => [
                'defaultPageSize' => self::PAGE_SIZE, 
                'pageSizeLimit' => self::PAGE_SIZE_LIMIT, 
                'pageSizeParam' => 'per-page',
            ],
            'query' => $query,
            'sort' => [
                'attributes' => [
                    'id',
                    'username',
                    'email',
                    'status',
                    'created_at',
                    'updated_at',
                    'role' => [
                        'asc' => ['a.item_name' => SORT_ASC],
                        'desc' => ['a.item_name' => SORT_DESC],
                    ],
                ],
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

        $query->andFilterWhere(['u.id' => $this->id])
              ->andFilterWhere(['u.status' => $this->status])
              ->andFilterWhere(['like', 'u.username', $this->username])
              ->andFilterWhere(['like', 'u.email', $this->email])
              ->andFilterWhere(['a.item_name' => $this->role]);

        return $dataProvider;
    }
}
