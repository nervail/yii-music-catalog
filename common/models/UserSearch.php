<?php

namespace common\models;

use common\models\User;
use yii\data\ActiveDataProvider;

class UserSearch extends User
{
    public $role;

    public function rules()
    {
        return [
            [['id', 'status'], 'integer'],
            [['username', 'email'], 'string'],
            [['role'], 'safe'],
        ];
    }

    public function search($params)
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
                'pageSize' => 10
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

        if (!($this->load($params) && $this->validate())) {
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
