<?php

namespace backend\modules\api\controllers;
    
use common\models\Genre;
use backend\modules\api\controllers\BaseApiController;
use yii\data\ActiveDataProvider;

class GenreController extends BaseApiController
{
    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['authenticator']['except'] = [
            'index',
            'view',
        ];

        return $behaviors;
    }

    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Genre::find(),
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);

        return $this->success($this->prepareResource($dataProvider, 'genres'));
    }

        public function actionView(int $id)
        {
            $item = $this->findModel($id, Genre::class);

            return $this->success($item);
        }
}