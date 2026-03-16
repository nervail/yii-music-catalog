<?php

namespace backend\controllers;

use common\models\User;
use common\models\UserSearch;
use Yii;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * Site controller
 */
class UserController extends Controller
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
                        'roles' => ['viewUsers'],
                    ],
                    // [
                    //     'allow' => true,
                    //     'actions' => ['view'],
                    //     'roles' => ['viewArtist'],
                    // ],
                    // [
                    //     'allow' => true,
                    //     'actions' => ['create'],
                    //     'roles' => ['createArtist'],
                    // ],
                    // [
                    //     'allow' => true,
                    //     'actions' => ['update'],
                    //     'roles' => ['updateArtist'],
                    // ],
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

    public function actionIndex()
    {
        $searchUser = new UserSearch();
        $dataProvider = $searchUser->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchUser,
        ]);
    }
}
