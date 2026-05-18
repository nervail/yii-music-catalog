<?php

namespace common\services;

use common\entities\Album;
use common\entities\Subscription;
use common\queue\jobs\AlbumReleaseNotificationJob;

class AlbumPublishService
{
    public function publish(Album $album): bool
    {
        if ($album->status === Album::STATUS_PUBLISHED) {
            return false;
        }

        $subscriptions = Subscription::find()
            ->where(['artist_id' => $album->artist_id])
            ->with('user')
            ->all();

        $publishedResult = $album->publish();

        $albumData = [
            'name' => $album->name,
            'artistName' => $album->artist->name,
        ];

        foreach ($subscriptions as $subscription) {
            $user = $subscription->user;

            if (!$user || !$user->email) {
                continue;
            }

            \Yii::$app->queue->push(new AlbumReleaseNotificationJob([
                'userEmail' => $user->email,
                'username' => $user->username,
                'albumData' => $albumData,
            ]));
        }

        return $publishedResult;
    }
}