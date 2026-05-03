<?php

namespace frontend\controllers\api;

use yii\filters\AccessControl;
use yii\rest\ActiveController;

class BaseApiController extends ActiveController
{
    public $resourceName;

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['access'] = [
            'class' => AccessControl::class,
            'rules' => [
                [
                    'allow' => true,
                    'actions' => ['index'],
                    'roles' => ["apiIndex{$this->resourceName}"],
                ],
                [
                    'allow' => true,
                    'actions' => ['view'],
                    'roles' => ["apiView{$this->resourceName}"],
                ],
                [
                    'allow' => true,
                    'actions' => ['options'],
                    'roles' => ['?', '@'],
                ],
            ],
        ];

        return $behaviors;
    }

    public function actions()
    {
        $actions = parent::actions();

        // disable the "delete" and "create" actions
        unset($actions['delete'], $actions['create'], $actions['update']);

        // customize the data provider preparation with the "prepareDataProvider()" method
        // $actions['index']['prepareDataProvider'] = [$this, 'prepareDataProvider'];

        return $actions;
    }
}