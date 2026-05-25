<?php

namespace common\repositories;

use common\entities\Subscription;
use common\exceptions\RepositoryException;

class SubscriptionRepository
{
    /**
     * Находит все подписки артиста вместе с данными пользователей.
     * * @param int $artistId
     * @return Subscription[]
     */
    public function findSubscriptionsWithUser(int $artistId): array
    {
        return Subscription::find()
            ->where(['artist_id' => $artistId])
            ->with('user')
            ->all();
    }

    public function save(Subscription $subscription): void
    {
        if (!$subscription->save()) {
            throw new RepositoryException();
        }
    }
}