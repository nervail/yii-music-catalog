<?php

namespace backend\modules\api\controllers;

use backend\modules\api\controllers\BaseApiController;
use common\entities\Item;
use common\search\ItemSearch;

class ItemController extends BaseApiController
{
    public function init()
    {
        parent::init();
        $this->serializer['collectionEnvelope'] = 'items';
    }

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
        $searchModel = new ItemSearch();
        $dataProvider = $searchModel->search($this->request->queryParams, '');

        return $this->success($dataProvider);
    }

    public function actionView(int $id)
    {
        $item = $this->findModel($id, Item::class);

        return $this->success($item);
    }
}