<?php

namespace backend\modules\api\controllers;

use backend\modules\api\controllers\BaseApiController;
use common\entities\Item;
use backend\modules\api\search\ItemSearch;
use common\entities\Album;
use yii\web\NotFoundHttpException;

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
        $item = Item::find()
            ->joinWith('album')
            ->andWhere([
                'items.id' => $id,
                'albums.status' => Album::STATUS_PUBLISHED,
            ])
            ->one();

        if ($item === null) {
            throw new NotFoundHttpException('Object not found');
        }

        return $this->success($item);
    }
}