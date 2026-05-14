<?php

namespace common\services;

use common\entities\Artist;
use common\entities\Subscription;
use yii\web\NotFoundHttpException;
use yii\web\ServerErrorHttpException;
use yii\web\UnprocessableEntityHttpException;

class SubscriptionService
{
    public function subscribe(int $artistId, int $userId): void
    {
        if (!Artist::find()->where(['id' => $artistId])->exists()) {
            throw new NotFoundHttpException("Artist not found");
        }

        if (Subscription::find()->where(['user_id' => $userId, 'artist_id' => $artistId])->exists()) {
            throw new UnprocessableEntityHttpException("You are already subscribed");
        }

        $model = new Subscription();
        $model->artist_id = $artistId;
        $model->user_id = $userId;

        if (!$model->save()) {
            throw new ServerErrorHttpException('Subscription saving error.');
        }
    }

    public function unsubscribe(int $artistId, int $userId): void
    {
        $subscription = Subscription::findOne(['user_id' => $userId, 'artist_id' => $artistId]);

        if (!$subscription) {
            throw new NotFoundHttpException("Subscription not found");
        }

        if ($subscription->delete() === false) {
            throw new ServerErrorHttpException('Unsubscription error.');
        }
    }
}