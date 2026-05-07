<?php

namespace backend\modules\admin\controllers;

use common\models\Artist;
use common\models\Item;
use common\models\ItemForm;
use common\models\ItemSearch;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;

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
                        'actions' => ['index'],
                        'roles' => ['indexItem'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['view'],
                        'roles' => ['viewItem'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['create'],
                        'roles' => ['createItem'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['update'],
                        'roles' => ['updateItem'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['delete'],
                        'roles' => ['deleteItem'],
                    ],
                ],
                'denyCallback' => function ($rule, $action) {
                    if (Yii::$app->user->isGuest) {
                        return \Yii::$app->response->redirect(['/admin/site/login']);
                    }
                    
                    throw new \yii\web\ForbiddenHttpException('У вас нет доступа');
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

    protected function findModel(int $id, ?array $with = [])
    {
        $query = Item::find()->where(['id' => $id]);

        if (!empty($with)) {
            $query->with($with);
        }

        if (($model = $query->one()) !== null) {
            return $model;
        }

        throw new NotFoundHttpException();
    }

    public function actionIndex()
    {
        $searchTrack = new ItemSearch();
        $dataProvider = $searchTrack->search(Yii::$app->request->queryParams);
        
        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchTrack,
        ]);
    }

    public function actionCreate()
    {
        $model = new ItemForm();
        $model->scenario = ItemForm::SCENARIO_CREATE;

        if ($model->load(Yii::$app->request->post())) {
            throw new NotFoundHttpException(print_r($model->attributes, true));
            $model->image = UploadedFile::getInstance($model, 'image');

            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Track created');
                return $this->redirect('/index');
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    public function actionUpdate($id)
    {
        $item = $this->findModel($id, ['genres', 'artist']);

        $model = new ItemForm();
        $model->scenario = ItemForm::SCENARIO_UPDATE;
        $model->setFromModel($item);

        if ($model->load(Yii::$app->request->post())) {
            $model->image = UploadedFile::getInstance($model, 'image');

            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Track updated');
                return $this->redirect('index');
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    public function actionView($id)
    {
        $model = $this->findModel($id, ['genres', 'artist']);

        return $this->render('view', [
            'model' => $model,
        ]);
    }

    public function actionDelete($id)
    {
        $model = $this->findModel($id);

        if (!$model->delete()) {
            Yii::$app->session->setFlash('error', 'Error while trying delete');
        } else {
            Yii::$app->session->setFlash('success', 'Track deleted');
        }

        return $this->redirect('index');
    }

}
