<?php

namespace backend\modules\api\controllers;

use backend\modules\api\controllers\BaseApiController;

/**
 * Site controller
 */
class SiteController extends BaseApiController
{

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['authenticator']['except'] = [
            'index',
        ]; 

        return $behaviors;
    }

    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        return $this->success([
            'status' => 'OK',
            'version' => '1.0.0',
            'message' => 'Music Catalog API',
            'time' => date('Y-m-d H:i:s'),
        ]);
    }
}
