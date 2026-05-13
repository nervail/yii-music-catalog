<?php

namespace backend\modules\api\controllers;

use backend\modules\api\controllers\BaseApiController;
use common\entities\Album;
use common\search\AlbumSearch;

class AlbumController extends BaseApiController
{
    public function init()
    {
        parent::init();
        $this->serializer['collectionEnvelope'] = 'albums';
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
        $searchModel = new AlbumSearch();
        $dataProvider = $searchModel->search($this->request->queryParams, '');

        return $this->success($dataProvider);
    }

    public function actionView(int $id)
    {
        $item = $this->findModel($id, Album::class);

        return $this->success($item);
    }
}