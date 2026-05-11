<?php

namespace backend\modules\admin\controllers;

use common\entities\Album;
use common\forms\AlbumForm;
use common\search\AlbumSearch;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;
use yii\web\UploadedFile;

/**
 * AlbumController implements the CRUD actions for Album model.
 */
class AlbumController extends Controller
{
    public const PAGE_SIZE = 20;

    public $layout = 'admin';

    /**
     * @inheritDoc
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
                        'roles' => ['indexAlbum'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['view'],
                        'roles' => ['viewAlbum'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['create'],
                        'roles' => ['createAlbum'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['update'],
                        'roles' => ['updateAlbum'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['delete'],
                        'roles' => ['deleteAlbum'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['get-albums'],
                        'roles' => ['@'],
                    ],
                ],
                'denyCallback' => function ($rule, $action) {
                    if (\Yii::$app->user->isGuest) {
                        return \Yii::$app->response->redirect(['/admin/site/login']);
                    }
                    
                    throw new \yii\web\ForbiddenHttpException('У вас нет доступа');
                }
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Album models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new AlbumSearch();
        $dataProvider = $searchModel->search(\Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Album model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $model = $this->findModel($id, ['artist', 'items']);

        $trackProvider = new ActiveDataProvider([
            'query' => $model->getItems(),
            'pagination' => [
                'pageSize' => 10,
            ],
            'sort' => [
                'defaultOrder' => ['id' => SORT_ASC],
            ]
        ]);

        return $this->render('view', [
            'model' => $model,
            'trackProvider' => $trackProvider,
        ]);
    }

    /**
     * Creates a new Album model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new AlbumForm();
        $model->scenario = AlbumForm::SCENARIO_CREATE;

        if ($model->load(\Yii::$app->request->post())) {
            $model->image = UploadedFile::getInstance($model, 'image');

            if ($model->save()) {
                \Yii::$app->session->setFlash('success', 'Album created');
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Album model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate(int $id)
    {
        $album = $this->findModel($id, ['artist', 'items']);

        $model = new AlbumForm();
        $model->scenario = AlbumForm::SCENARIO_UPDATE;
        $model->setFromModel($album);

        if ($model->load(\Yii::$app->request->post())) {
            $model->image = UploadedFile::getInstance($model, 'image');

            if ($model->save()) {
                \Yii::$app->session->setFlash('success', 'Album updated');
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Album model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        if ($this->findModel($id)->delete()) {
            \Yii::$app->session->setFlash('success', 'Album deleted');
        } else {
            \Yii::$app->session->setFlash('error', 'Error deleting album');
        }

        return $this->redirect(['index']);
    }

    /**
     * Finds the Album model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @param array|null $with - related records to load with the model
     * @return Album the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel(int $id, ?array $with = [])
    {
        $query = Album::find()->where(['id' => $id]);

        if (!empty($with)) {
            $query->with($with);
        }

        if (($model = $query->one()) !== null) {
            return $model;
        }

        throw new NotFoundHttpException();
    }

    public function actionGetAlbums()
    {
        \Yii::$app->response->format = Response::FORMAT_JSON;

        $model = new AlbumForm();
        $model->scenario = AlbumForm::SCENARIO_GET_ALBUMS;

        if (! ($model->load(\Yii::$app->request->get(), '')) || !($model->validate())) {
            throw new NotFoundHttpException('Invalid parameters');
        }
        
        $query = Album::find()
            ->select(['id', 'name AS text'])
            ->orderBy(['name' => SORT_ASC]);

        $query->andWhere(['artist_id' => $model->artist_id]);

        $query->andFilterWhere(['like', 'name', $model->name]);

        $pageSize = self::PAGE_SIZE;
        $page = \Yii::$app->request->get('page', 1);

        $query->limit($pageSize)
            ->offset(($page - 1) * $pageSize);

        $albums = $query->asArray()->all();

        return [
            'results' => $albums,
            'pagination' => [
                'more' => count($albums) === $pageSize
            ]
        ];
    }
}
