<?php

namespace common\rbac;

use yii\rbac\Rule;
use common\models\User;

class UpdateUserRule extends Rule 
{
    public $name = 'updateUserRule';

    public function execute($userId, $item, $params)
    {
        if (!isset($params['model'])) {
            return false;
        }

        /** @var User $model */
        $model = $params['model'];

        if ($model->id == $userId) {
            return false;
        }

        if ($model->isAdmin()) {
            return false;
        }

        return true;
    }
}