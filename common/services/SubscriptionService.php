<?php

namespace common\services;

use common\entities\Artist;
use common\entities\Subscription;
use yii\web\NotFoundHttpException;
use yii\web\ServerErrorHttpException;
use yii\web\TooManyRequestsHttpException;
use yii\web\UnprocessableEntityHttpException;

class SubscriptionService
{
    private function checkRateLimit(int $userId, string $action): void
    {
        $cacheKey = "subscription-action:{$action}:{$userId}";

        if (!\Yii::$app->cache->add($cacheKey, true, 2)) {
            throw new TooManyRequestsHttpException(
                'Слишком много запросов. Попробуйте через несколько секунд.'
            );
        }
    }

    public function subscribe(int $artistId, int $userId): void
    {
        $this->checkRateLimit($userId, 'subscribe');

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
        $this->checkRateLimit($userId, 'unsubscribe');

        $subscription = Subscription::findOne(['user_id' => $userId, 'artist_id' => $artistId]);

        if (!$subscription) {
            throw new NotFoundHttpException("Subscription not found");
        }

        if ($subscription->delete() === false) {
            throw new ServerErrorHttpException('Unsubscription error.');
        }
    }
}