<?php

namespace backend\controllers;

use common\models\Item;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * Site controller
 */
class ItemController extends Controller
{

    public $layout = 'admin';

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['error'],
                    ],
                    [
                        'allow' => true,
                        'roles' => ['admin'],
                    ],
                ],
                'denyCallback' => function ($rule, $action) {
                    Yii::$app->response->redirect(['/site/login']);
                }
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => \yii\web\ErrorAction::class,
            ],
        ];
    }

    protected function findModel(int $id)
    {
        if (($model = Item::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException();
    }

    public function actionIndex()
    {
        $query = Item::find()->where(['status' => 1]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10,
            ]
        ]);
        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionSeed()
    {
        $items = [
            [
                'name' => 'Track One',
                'description' => 'First test track',
                'image_url' => '/images/test1.jpg',
                'artist_id' => 1,
                'genre_id' => 4,
            ],
            [
                'name' => 'Track Two',
                'description' => 'Second test track',
                'image_url' => '/images/test2.jpg',
                'artist_id' => 1,
                'genre_id' => 4,
            ],
            [
                'name' => 'Track Three',
                'description' => 'Third test track',
                'image_url' => '/images/test3.jpg',
                'artist_id' => 1,
                'genre_id' => 1,
            ],
        ];

        foreach ($items as $data) {
            $item = new Item();
            $item->attributes = $data;
            $item->save();
        }

        return "done";
    }


}
