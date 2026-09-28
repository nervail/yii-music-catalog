<?php

namespace common\services;

use common\entities\Album;
use common\queue\jobs\AlbumReleaseNotificationJob;

class AlbumNotificationService
{
    public function sendAlbumNotifications(Album $album, array $subscriptions): void
    {
        $albumData = [
            'name' => $album->name,
            'artistName' => $album->artist->name,
            'link' => \Yii::$app->params['frontendUrl'] . '/albums/' . $album->id,
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
    }
}