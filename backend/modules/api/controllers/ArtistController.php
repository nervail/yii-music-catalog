<?php

namespace backend\modules\api\controllers;

use common\entities\Artist;
use backend\modules\api\controllers\BaseApiController;
use yii\data\ActiveDataProvider;

class ArtistController extends BaseApiController
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
            'query' => Artist::find(),
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);

        return $this->success($this->prepareResource($dataProvider, 'artists'));
    }

    public function actionView(int $id)
    {
        $item = $this->findModel($id, Artist::class);

        return $this->success($item);
    }
}