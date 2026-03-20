<?php

namespace backend\controllers;

use backend\models\UpdateUserForm;
use common\models\User;
use common\models\UserSearch;
use Yii;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;

/**
 * Site controller
 */
class UserController extends Controller
{
    private $_models = [];

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
                        'actions' => ['index', 'view'],
                        'roles' => ['viewUsers'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['update'],
                        'roles' => ['updateUser'],
                        'roleParams' => function ($rule) {
                            return [
                                'model' => $this->findModel(Yii::$app->request->get('id'))
                            ];
                        },
                    ],
                    // [
                    //     'allow' => true,
                    //     'actions' => ['delete'],
                    //     'roles' => ['deleteArtist'],
                    // ],
                ],
                'denyCallback' => function ($rule, $action) {
                    if (Yii::$app->user->isGuest) {
                        return Yii::$app->response->redirect(['/site/login']);
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

    protected function findModel(int $id): User
    {
        if (!isset($this->_models[$id])) {
            $model = User::findOne($id);
            if ($model === null) {
                throw new NotFoundHttpException();
            }

            if (!\Yii::$app->user->can('superAdmin')) {
                if (
                    $model->isSuperAdmin() ||
                    $model->isAdmin() ||
                    $model->id == \Yii::$app->user->id
                    ) {
                        throw new ForbiddenHttpException('Доступ запрещен');
                    }
            }
            $this->_models[$id] = $model;
        }

        return $this->_models[$id];
    }

    public function actionIndex()
    {
        $searchUser = new UserSearch();
        $dataProvider = $searchUser->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchUser,
        ]);
    }

    public function actionView(int $id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    public function actionUpdate(int $id)
    {
        $user = $this->findModel($id);
        $model = new UpdateUserForm($user);

        if ($model->load(\Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'User updated');
            return $this->redirect(['view', 'id' => $user->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }
}
