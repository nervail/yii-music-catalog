<?php

namespace backend\modules\api\controllers;

use backend\modules\api\components\VerifiedEmailFilter;
use backend\modules\api\controllers\BaseApiController;
use common\services\SubscriptionService;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\ForbiddenHttpException;

class SubscriptionController extends BaseApiController
{
    public function init()
    {
        parent::init();
        $this->serializer['collectionEnvelope'] = 'subscriptions';
    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['verbs'] = [
            'class' => VerbFilter::class,
            'actions' => [
                'subscribe'  => ['post'],
                'unsubscribe'   => ['post'],
            ],
        ];

        $behaviors['emailVerified'] = [
            'class' => VerifiedEmailFilter::class,
        ];

        $behaviors['access'] = [
            'class' => AccessControl::class,
            'rules' => [
                [
                    'allow' => true,
                    'actions' => ['subscribe'],
                    'roles' => ['subscribeArtist'],
                ],
                [
                    'allow' => true,
                    'actions' => ['unsubscribe'],
                    'roles' => ['unsubscribeArtist'],
                ],
            ],
        ];
        return $behaviors;
    }

    public function actionSubscribe(int $id)
    {
        $userId = \Yii::$app->user->id;
        $service = new SubscriptionService();

        $service->subscribe($id, $userId);

        return $this->success();
    }

    public function actionUnsubscribe(int $id)
    {
        $userId = \Yii::$app->user->id;
        $service = new SubscriptionService();
        
        $service->unsubscribe($id, $userId);

        return $this->success();
    }
}