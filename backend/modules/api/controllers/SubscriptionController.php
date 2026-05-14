<?php

namespace backend\modules\api\controllers;

use backend\modules\api\components\VerifiedEmailFilter;
use backend\modules\api\controllers\BaseApiController;
use common\services\SubscriptionService;
use yii\filters\VerbFilter;


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

        $behaviors['emailVerified'] = [
            'class' => VerifiedEmailFilter::class,
        ];

        $behaviors['verbs'] = [
            'class' => VerbFilter::class,
            'actions' => [
                'subscribe'  => ['post'],
                'unsubscribe'   => ['post'],
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