<?php

namespace backend\modules\api\controllers;

use common\models\Album;
use backend\modules\api\controllers\BaseApiController;
use yii\data\ActiveDataProvider;

class AlbumController extends BaseApiController
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
            'query' => Album::find(),
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);

        return $this->success($this->prepareResource($dataProvider, 'albums'));
    }

    public function actionView(int $id)
    {
        $item = $this->findModel($id, Album::class);

        return $this->success($item);
    }
}