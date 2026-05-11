<?php

namespace backend\modules\api\controllers;

use common\entities\Item;
use backend\modules\api\controllers\BaseApiController;
use yii\data\ActiveDataProvider;

class ItemController extends BaseApiController
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
            'query' => Item::find(),
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);

        return $this->success($this->prepareResource($dataProvider, 'items'));
    }

    public function actionView(int $id)
    {
        $item = $this->findModel($id, Item::class);

        return $this->success($item);
    }
}