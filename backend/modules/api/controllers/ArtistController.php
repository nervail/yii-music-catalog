<?php

namespace backend\modules\api\controllers;

use backend\modules\api\controllers\BaseApiController;
use backend\modules\api\entities\Artist;
use backend\modules\api\search\ArtistSearch;

class ArtistController extends BaseApiController
{
    public function init()
    {
        parent::init();
        $this->serializer['collectionEnvelope'] = 'artists';
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
        $searchModel = new ArtistSearch();
        $dataProvider = $searchModel->search($this->request->queryParams, '');

        return $this->success($dataProvider);
    }

    public function actionView(int $id)
    {
        $item = $this->findModel($id, Artist::class);

        return $this->success($item);
    }
}