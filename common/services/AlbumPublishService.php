<?php

namespace common\services;


use common\entities\Album;
use common\exceptions\ALbumPublishedException;
use common\repositories\AlbumRepository;
use common\repositories\SubscriptionRepository;
use common\services\AlbumNotificationService;


class AlbumPublishService
{
    public function __construct(
        private SubscriptionRepository $subscriptions,
        private AlbumRepository $albums,
        private AlbumNotificationService $albumNotificationService
    ) {
    }

    public function publish(Album $album): void
    {
        if ($album->status === Album::STATUS_PUBLISHED) {
            throw new ALbumPublishedException();
        }

        $subscriptions = $this->subscriptions->findSubscriptionsWithUser($album->artist_id);

        $this->albums->publish($album);

        $this->albumNotificationService->sendAlbumNotifications($album, $subscriptions);
    }
}