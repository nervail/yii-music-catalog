<?php

namespace backend\modules\api\components;

use yii\base\ActionFilter;
use yii\web\ForbiddenHttpException;
use yii\web\UnauthorizedHttpException;

class VerifiedEmailFilter extends ActionFilter
{
    public function beforeAction($action): bool
    {
        /** @var \common\entities\User $user */
        $user = \Yii::$app->user->identity;

        if ($user === null) {
            throw new UnauthorizedHttpException('Login required.');
        }

        if (!$user->isVerified()) {
            throw new ForbiddenHttpException('Please verify your email.');
        }

        return parent::beforeAction($action);
    }
}