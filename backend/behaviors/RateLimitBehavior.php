<?php

namespace backend\behaviors;

use yii\base\ActionFilter;
use yii\base\InvalidConfigException;
use yii\web\ServerErrorHttpException;
use yii\web\TooManyRequestsHttpException;

class RateLimitBehavior extends ActionFilter
{
    public array $actions = [];

    public function beforeAction($action): bool
    {
        if (!parent::beforeAction($action)) {
            return false;
        }

        $limits = $this->actions[$action->id] ?? [];

        foreach ($limits as $name => $config) {
            $this->checkLimit($name, $config, $action);
        }

        return true;
    }

    private function checkLimit(
        string $name,
        array $config,
        $action
    ): void {
        $limit = (int)($config['limit'] ?? 0);
        $window = (int)($config['window'] ?? 0);
        $identifierCallback = $config['identifier'] ?? null;

        if (
            $limit <= 0 ||
            $window <= 0 ||
            !is_callable($identifierCallback)
        ) {
            throw new InvalidConfigException(
                "Invalid rate limit configuration for '{$name}'."
            );
        }

        $identifier = (string)call_user_func(
            $identifierCallback,
            $action
        );

        if ($identifier === null) {
            return;
        }

        $identifier = (string)$identifier;

        $cacheKey = implode(':', [
            'rate-limit',
            $action->uniqueId,
            $name,
            hash('sha256', $identifier),
        ]);

        $lockKey = 'rate-limit-lock:' . hash('sha256', $cacheKey);

        $cache = \Yii::$app->cache;
        $mutex = \Yii::$app->mutex;
        $now = time();

        if (!$mutex->acquire($lockKey, 1)) {
            throw new ServerErrorHttpException(
                'Rate limiter is temporarily unavailable.'
            );
        }

        try {
            $data = $cache->get($cacheKey);

            if ($data === false || $data['resetAt'] <= $now) {
                $cache->set(
                    $cacheKey,
                    [
                        'count' => 1,
                        'resetAt' => $now + $window,
                    ],
                    $window
                );

                return;
            }

            if ($data['count'] >= $limit) {
                $retryAfter = max(1, $data['resetAt'] - $now);

                \Yii::$app->response->headers->set(
                    'Retry-After',
                    (string)$retryAfter
                );

                throw new TooManyRequestsHttpException(
                    'Too many requests.'
                );
            }

            $data['count']++;

            $remaining = max(1, $data['resetAt'] - $now);

            $cache->set(
                $cacheKey,
                $data,
                $remaining
            );
        } finally {
            $mutex->release($lockKey);
        }
    }
}
