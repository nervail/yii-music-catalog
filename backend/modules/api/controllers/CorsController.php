<?php

namespace backend\modules\api\controllers;

class CorsController extends BaseApiController
{
    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['authenticator']['except'] = ['index'];

        return $behaviors;
    }

    public function actionIndex()
    {
        return null;
    }
}
