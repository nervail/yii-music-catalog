<?php

namespace backend\modules\api\controllers;
    
use common\entities\Genre;
use backend\modules\api\controllers\BaseApiController;
use common\search\GenreSearch;

class GenreController extends BaseApiController
{
    public function init()
    {
        parent::init();
        $this->serializer['collectionEnvelope'] = 'genres';
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
        $searchModel = new GenreSearch();
        $dataProvider = $searchModel->search($this->request->queryParams, '');

        return $this->success($dataProvider);
    }

        public function actionView(int $id)
        {
            $item = $this->findModel($id, Genre::class);

            return $this->success($item);
        }
}